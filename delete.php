<?php
require_once 'vendor/autoload.php';
require_once "random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
//"DefaultEndpointsProtocol=https;AccountName=artstoragedico;AccountKey=QukAEjBNIkDmVRw2fSS6T7LTggHux+09tapv2Rqihc6u8t8cZmHOazClaoEcAA7wW5/q8Ad4IdH+HICnBdFbxw==;EndpointSuffix=core.windows.net";
// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
	try{
        // Delete container.
        echo "Deleting Blob".PHP_EOL;
		$fileToUpload = $_GET['name'];
		echo "<br />";
		$blobClient->deleteBlob($_GET["container"], $fileToUpload);

		header('location:phpQS.php?container='.$containerName);        
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo "<h3 style='color:red'><b>".$code.": ".$error_message."</b></h3>";
		echo '<div class="button_cont" id="btn1"  align="center"><a class="btn red" href="phpQs.php" rel="nofollow noopener">Kembali</a></div>';
    }
?>