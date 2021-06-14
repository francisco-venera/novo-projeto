<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 18/12/2019
 * Time: 08:01 AM
 */

namespace common\components;

use Aws;
use common\entities\Admin;
use common\entities\Client;
use common\exceptions\FeedbackException;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Stream;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\Optimizers\Gifsicle;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Pngquant;
use Spatie\ImageOptimizer\Optimizers\Svgo;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

class S3 extends Component
{
    public $websiteEndpoint;
    public $websiteSymbolicEndpoint;

    /**
     * Define se a pasta do clinete (token) vai ser utilizado automaticamente
     * @var bool
     */
    private $isClient = true;

    /**
     * Define se a pasta da admin (token) vai ser utilizado automaticamente
     * @var bool
     */
    private $isAdmin = true;

    /**
     * token do admin
     * @var string
     */
    private $tokenAdm;

    /**
     * token do cliente
     * @var string
     */
    private $tokenClient;

    /**
     * pasta privada do S3
     * @var string
     */
    private $privateFolder = 'private';

    /**
     * pasta pública do S3
     * @var string
     */
    private $publicFolder = 'public';

    /**
     * Pasta inicial do bucket
     * @var string
     */
    private $initFolder;

    /**
     * @var array specifies the AWS credentials
     */
    public $credentials = [];

    /**
     * @var string specifies the AWS region
     */
    public $region = null;

    /**
     * @var string specifies the AWS version
     */
    public $version = null;

    /**
     * @var string specifies the AWS version
     */
    public $bucket = null;

    /**
     * @var array specifies extra params
     */
    public $extra = [];

    /**
     * @var AWS SDK instance
     */
    protected $s3;

    /**
     * Instancia (se necessário) o objeto do AWS S3
     * @return Aws instance
     */
    public function getAwsSdk() {
        if (empty($this->s3) || !$this->s3 instanceof Aws\S3\S3Client)
            $this->setAwsSdk();

        return $this->s3;
    }

    /**
     * Sets the AWS SDK instance
     */
    public function setAwsSdk() {
        $this->s3 = new Aws\S3\S3Client(array_merge([
            'credentials' => $this->credentials,
            'region' => $this->region,
            'version' => $this->version
        ], $this->extra));
    }

    public function getTokenClient($clientId = null) {
        if(!$this->tokenClient)
            $this->tokenClient = Client::getToken($clientId);

        return $this->tokenClient;
    }

    public function setTokenClient($clientId = null) {
        $this->tokenClient = Client::getToken($clientId);

        return $this->tokenClient;
    }

    public function getTokenAdmin($adminId = null) {
        if(!$this->tokenAdm)
            $this->tokenAdm = Admin::getToken($adminId);

        return $this->tokenAdm;
    }

    public function setTokenAdmin($adminId = null) {
        $this->tokenAdm = Admin::getToken($adminId);
    }

    public function init() {
        $this->initFolder = $this->privateFolder;
        $this->setAwsSdk();
    }

    /**
     * Se for um array, encontrou o arquivo.
     * Se for uma string, deu erro.
     * @param $key
     * @param bool $pathFixed
     * @param string $saveAs
     * @return string
     */
    public function getFile($key, $pathFixed = false, $saveAs = '') {
        try {
            if(!$this->exists($key, $pathFixed))
                throw new FeedbackException("O arquivo {$this->getFilenameByDirectory($key)} não foi encontrado.");

            $file = $this->s3->getObject([
                'Bucket' => $this->bucket,
                'Key' => $pathFixed ? $key : $this->getFilePath($key),
            ]);

            return $file;
        } catch(\Exception $e) {
            $mensagem = "O arquivo {$this->getFilenameByDirectory($key)} não foi encontrado";
            if($e instanceof FeedbackException)
                $mensagem = $e->getMessage();

            return $mensagem;
        }
    }

    public function createPresignedRequest($file, $time = null) {
        return $this->s3->createPresignedRequest($file, $time);
    }

    public function getFolderClient() {
        return "{$this->getInitFolder()}/{$this->getTokenAdmin()}/{$this->getTokenClient()}/";
    }

    public function getFolderAdmin() {
        return "{$this->getInitFolder()}/{$this->getTokenAdmin()}/";
    }

    /**
     * Cria uma nova pasta
     * $this->createFolder('exemploPasta/Exemplo2')
     * @param $key
     * @return bool|string
     */
    public function createFolder($key) {
        try {
            $path = "{$key}/";

            $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $path,
            ]);

            if($this->exists($path, true)) return true;

            throw new FeedbackException('Não foi possível criar a pasta.');
        } catch (\Exception $e) {
            $mensagem = 'Não foi possível criar a pasta.';
            if($e instanceof FeedbackException) $mensagem = $e->getMessage();

            return $mensagem;
        }
    }

    /**
     * Exclui um arquivo do S3
     * @param $key
     * @return string
     */
    public function deleteObject($key, $fixedPath = false) {
        try {

            $this->s3->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $fixedPath ? $key : $this->getFilePath($key),
            ]);

            // Se o arquivo não existe, a exclusão funcionou
            if(!$this->exists($key, $fixedPath)) return true;

            throw new FeedbackException('Não foi possível excluir o arquivo.');

        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return "Não foi possível excluir o arquivo {$key} - {$e->getMessage()}";
        }
    }

    public function headersDownload($filename, $contentType) {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Content-Disposition: attachment; filename="'. $filename. '"');
        header("Content-Type: {$contentType}");
    }

    /**
     * Método a ser chamado quando já tiver o objeto (getFile())
     * @param $object
     * @param string $filename
     * @throws FeedbackException
     */
    public function downloadObject($object, $filename = null) {
        if(!$filename) {
            if(isset($object['@metadata']['effectiveUri'])) {
                $explodeName = explode('/', $object['@metadata']['effectiveUri']);
                $filename = array_pop($explodeName);
            } else {
                $filename = 'download.txt';
            }
        }

        $this->headersDownload($filename, $object['ContentType']);

        if(isset($object['Body']))
            echo $object->get('Body');
        else
            throw new FeedbackException('Não foi possível buscar o arquivo.');
    }

    /**
     * Utilizar o método exists() antes para validar se o objeto existe
     * @param $objectName
     * @param string $filename
     * @return mixed
     */
    public function downloadObjectByName($objectName, $filename = null) {
        if(!$filename) {
            $explodeName = explode('/', $objectName['@metadata']['effectiveUri']);
            $filename = array_pop($explodeName);
        }

        $object = $this->getFile($objectName, true);
        $this->headersDownload($filename, $object->get('ContentType'));

        if(isset($object['Body']))
            return $object->get('Body');
        else
            return false;
    }

    /**
     * Do a file upload
     *
     * @param $keyname
     * @param $tempfile
     * @return bool
     */
    public function uploadObject($keyname, $tempfile, $pathFixed = false, $acl = null) {
        try {
            $path = $pathFixed ? $keyname : $this->getFilePath($keyname);

            if ($this->isImage($tempfile)) {
                $optimizer =  (new OptimizerChain())
                    ->addOptimizer(new Jpegoptim([
                        '-m70',
                        '--strip-all',
                        '--all-progressive',
                    ]))

                    ->addOptimizer(new Pngquant([
                        '--quality 65-80',
                        '--force',
                    ]))

                    ->addOptimizer(new Optipng([
                        '-i0',
                        '-o2',
                        '-quiet',
                    ]))

                    ->addOptimizer(new Svgo([
                        '--disable=cleanupIDs',
                    ]))

                    ->addOptimizer(new Gifsicle([
                        '-b',
                        '-O3',
                    ]));

                $optimizer->setTimeout(60)->optimize($tempfile);
            }

            $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $path,
                'Body'   => fopen($tempfile, 'r+'),
            ]);

            if($this->exists($keyname, $pathFixed)) return true;
        } catch (\Exception $e) {
            $mensagem = 'Não foi possível salvar o arquivo.';
            return $mensagem;
        }
    }

    /**
     * Do a create folder
     *
     * @param $keyname
     * @param $tempfile
     * @return bool
     */
    public function newFolder($keyname, $pathFixed = false) {
        try {
            $path = $pathFixed ? $keyname : $this->getFilePath($keyname);

            $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $path.'/',
            ]);

            if($this->exists($keyname, $pathFixed)) return true;
        } catch (\Exception $e) {
            $mensagem = 'Não foi possível salvar a pasta.';
            return $mensagem;
        }
    }

    /**
     * Verifica se o mimetype é uma imagem.
     *
     * @param $path
     * @return bool
     * @throws FeedbackException
     */
    public function isImage($path)
    {
        if (function_exists('finfo_open') === false)
            throw new FeedbackException('A biblioteca `finfo` não foi encontrada. Verificar se a instalação foi executada corretamente.');

        $mime = $this->getMimeType($path);

        if (substr($mime, 0, 6) === 'image/')
            return true;
        return false;
    }

    public function getMimeType($path) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mime;
    }

    private function getFilenameByDirectory($directory) {
        $arrayDirectory = explode('/', $directory);
        return array_pop($arrayDirectory);
    }

    public function renameFile($oldName, $newName, $pathFixed = false) {
        try {
            // Se o arquivo não existe
            if(!$this->exists($oldName, $pathFixed))
                throw new FeedbackException("O arquivo \"{$this->getFilenameByDirectory($oldName)}\" não foi encontrado.");

            // Se o novo nome é igual ao antigo
            if($oldName === $newName)
                throw new FeedbackException("O novo nome não pode ser igual ao antigo ({$this->getFilenameByDirectory($oldName)})");

            // Se já existe um arquivo com o novo nome informado
            if($this->exists($newName, $pathFixed))
                throw new FeedbackException(nl2br("O arquivo \"{$this->getFilenameByDirectory($newName)}\" já existe. Favor informar outro nome."));

            $oldKeyname = $pathFixed ? $oldName : $this->getFilePath($oldName);
            $newKeyname = $pathFixed ? $newName : $this->getFilePath($newName);

            // Cria uma cópia do objeto com o nome novo
            $this->s3->copyObject([
                'Bucket'     => $this->bucket,
                'Key'        => $newKeyname,
                'CopySource' => "$this->bucket/$oldKeyname"
            ]);

            // Verifica se o novo arquivo existe e exclui o antigo
            if($this->exists($newName, $pathFixed) && $this->deleteObject($oldName, $pathFixed))
                return true;

            throw new FeedbackException("Não foi possível renomear o arquivo {$this->getFilenameByDirectory($oldName)} para {$this->getFilenameByDirectory($newName)}");
        } catch(\Exception $e) {
            $mensagem = 'Não foi possível renomear o arquivo.';
            if ($e instanceof FeedbackException) $mensagem = $e->getMessage();

            return $mensagem;
        }

    }

    public function renameFolder($oldName, $newName, $pathFixed = false) {
        try {
            if($oldName === $newName)
                throw new FeedbackException("O novo nome não pode ser igual ao antigo ({$this->getFilenameByDirectory($oldName)})");

            $results = $this->s3->getPaginator('ListObjects', array(
                'Bucket' => $this->bucket,
                'Prefix' => "$oldName/"
            ));

            foreach ($results as $result) {
                if(empty($result['Contents']))
                    throw new FeedbackException("A pasta {$oldName} não existe.");

                foreach ($result['Contents'] as $object) {
                    $newNameFull = str_replace($oldName, $newName, $object['Key']);
                    //rename file, set variable to false if unsuccessful for even one file
                    if(!$this->renameFile($object['Key'], $newNameFull, $pathFixed))
                        throw new FeedbackException("{$this->getFilenameByDirectory($oldName)}");
                }
            }

            return true;
        } catch(\Exception $e) {
            $mensagem = 'Não foi possível renomear a pasta.';
            if($e instanceof FeedbackException) $mensagem = $e->getMessage();

            return $mensagem;
        }
    }

    public function getDirectoryS3($clientId = null) {

        if($clientId) {
            $this->setTokenClient($clientId);
        }

        $directoryS3 = $this->getInitFolder();

        if($this->isAdmin && $this->isClient){
            $directoryS3 .= $this->getTokenAdmin().'/'.$this->getTokenClient().'/';
        } elseif($this->isAdmin){
            $directoryS3 .= $this->getTokenAdmin().'/';
        }

        return $directoryS3;
    }

    /**
     * Execute a upload file
     *
     * @param $key Name of file
     * @param $sourceFile TempPHP file
     * @return bool
     */
    public function uploadFile($key, $sourceFile) {
        return $this->doUpload($key, $sourceFile);
    }

    /**
     * Do upload files with a async promise
     * @param $key Name of file
     * @param $sourceFile TempPHP file
     * @return Promise
     */
    public function uploadFileAsync($key, $sourceFile, $pathFixed = false, $acl = null) {
        $promise = new Promise(function () use (&$promise, $key, $sourceFile, $pathFixed, $acl) {
            if ($this->uploadObject($key, $sourceFile, $pathFixed, $acl))
                $promise->resolve('Upload realizado com sucesso!');
            else
                throw new \Exception('Não foi possível realizar o upload');
        });
        return $promise;
    }

    /**
     * Delete one or more files
     * @param array|string $keysPrefix name of files
     * @return bool|void
     */
    public function deleteObjects($keysPrefix, $fixedPath = false) {
        if (is_array($keysPrefix)) {
            foreach ($keysPrefix as $key)
                $this->doDeleteFile($key, $fixedPath);
            return true;
        } else {
            return $this->doDeleteFile($keysPrefix, $fixedPath);
        }
    }

    /**
     * Verify if a file/folder exists
     * @param $filePath path of file/folder
     * @return array
     */
    public function exists($keyname, $pathFixed = false) {
        $path = ($pathFixed == true ? $keyname : $this->getFilePath($keyname));

        return $this->s3->doesObjectExist(
            $this->bucket,
            $path
        );
    }

    public function getObjectUrlBasic($path, $pathFixed = false) {
        return $this->s3->getObjectUrl(
            $this->bucket,
            $pathFixed ? $path : $this->getFilePath($path)
        );
    }

    /**
     * @param $path
     * @param string $time
     * @param null $filename
     * @param bool $pathFixed
     * @return string
     */
    public function getObjectUrlDownload($path, $time = '+1 hour', $filename = null, $pathFixed = false)
    {
        $content = [
            'Bucket' => $this->bucket,
            'Key'    => $pathFixed ? $path : $this->getFilePath($path),
        ];

        if ($filename) {
            $content['ResponseContentDisposition'] = "attachment; filename={$filename}";
            $content['ResponseCacheControl'] = "no-cache";
        }

        $cmd = $this->s3->getCommand('GetObject', $content);
        $signed_url = $this->s3->createPresignedRequest($cmd, $time);

        return (string) $signed_url->getUri();
    }

    /**
     * @param $path
     * @param string $time
     * @param bool $pathFixed
     * @return mixed
     */
    public function getOBjectUrl($path, $time = '+1 hour', $pathFixed = false)
    {
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key'    => $pathFixed ? $path : $this->getFilePath($path),
        ]);

        $signed_url = $this->s3->createPresignedRequest($cmd, $time);

        return (string) $signed_url->getUri();
    }

    /**
     * @param $filePath
     * @return Stream
     */
    public function readFile($filePath) {
        return Psr7\stream_for(file_get_contents($this->getDocFolder() . $filePath));
    }

    /**
     * @param $filePath
     * @param bool $replace
     * @return mixed|string
     */
    public function getFilePath($filePath, $clientId = null, $replace = true) {
        if($replace)
            return str_replace('//','/',$this->getDirectoryS3($clientId) . $filePath);

        return $this->getDirectoryS3($clientId) . $filePath;
    }

    /**
     * Return the fullpath of documentos root folder
     * @return string
     */
    public function getDocFolder() {
        return Yii::getAlias(self::ROOT_FOLDER) . DIRECTORY_SEPARATOR;
    }

    /**
     * Create new folder
     * @param $folder path of new folder
     * @return bool
     */
    protected function doCreateFolder($folder) {
        return FileHelper::createDirectory($this->getDocFolder() . $folder);
    }

    /**
     * Verify if folder exists
     * @param $keyname path of the folder
     * @return bool
     */
    protected function isFolderExists($keyname) {
        return is_dir(self::getDocFolder() . $keyname);
    }

    /**
     * Verify if File exists
     * @param $keyname path of file
     * @return array
     */
    protected function isFileExists($keyname) {
        return file_exists(self::getDocFolder() . $keyname);
    }

    /**
     * Remove um arquivo
     *
     * @param $path
     * @return bool
     * @throws \Exception
     */
    protected function doDeleteFile($path, $fixedPath = false) {
         try {
             if ($this->exists($path, $fixedPath)) {
                 if ($this->deleteObject($path, $fixedPath)) return true;
                 throw new FeedbackException('Falha na exclusão do arquivo.');
             }

             throw new FeedbackException('Arquivo não encontrado.');
         } catch (\Exception $e) {
            $mensagem = 'Não foi possível excluir o arquivo';

            if($e instanceof FeedbackException) $mensagem = $e->getMessage();

            Yii::error($mensagem);

            return $mensagem;
         }
    }

    /**
     * @return string
     */
    public function getPrivateFolder()
    {
        return $this->privateFolder;
    }

    /**
     * @param string $privateFolder
     */
    public function setPrivateFolder($privateFolder)
    {
        $this->privateFolder = $privateFolder;
    }

    /**
     * @return string
     */
    public function getPublicFolder()
    {
        return $this->publicFolder;
    }

    /**
     * @param string $publicFolder
     */
    public function setPublicFolder($publicFolder)
    {
        $this->publicFolder = $publicFolder;
    }

    /**e
     * Recupera a pasta inicial (private ou public)
     * @return mixed
     */
    public function getInitFolder()
    {
        return $this->initFolder.'/';
    }

    /**
     * Seta a pasta inicial (private ou public)
     * @param mixed $initFolder
     */
    public function setInitFolder($initFolder)
    {
        $this->initFolder = $initFolder;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * @return bool
     */
    public function isClient()
    {
        return $this->isClient;
    }

    /**
     * @param bool $isClient
     */
    public function setIsClient($isClient)
    {
        $this->isClient = $isClient;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * Retorna o tamanho de um arquivo.
     *
     * @param $file
     * @return int|boolean bytes or false on error.
     */
    public function getFileSize($file) {
        $object = $this->getFile($file);

        if(is_string($object))
            return 0;

        return $object->get('ContentLength');
    }
}
