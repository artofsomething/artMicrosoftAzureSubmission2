<html>
<head>
<title>Submission 2 Microsoft Azure DicodingT</title>

<style>
.inner_container{
	width:650px; // this can be what ever unit you want, you just have to define it
	margin:0 auto;
}
.container{
	width:100%;
}
.btn {
	color: #fff !important;
	text-decoration:none;
	text-transform: uppercase;	
	padding: 10px;
	border-radius: 5px;
	display: inline-block;
	border: none;
	width:150px;
}
.btn:hover {	
	letter-spacing: 1px;
	-webkit-box-shadow: 0px 5px 40px -10px rgba(0,0,0,0.57);
	-moz-box-shadow: 0px 5px 40px -10px rgba(0,0,0,0.57);
	box-shadow: 5px 40px -10px rgba(0,0,0,0.57);
	transition: all 0.2s ease 0s;
}
.red{
	background: #ed3330;
}
.red:hover{
	background: #434343;
}
.blue{
	background: #42c5f4;
}
.blue:hover{
	background: #434343;
}
#btn1,#btn2{
	display:inline;
}
</style>
</head>
<body>
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

// Create container options object.
$createContainerOptions = new CreateContainerOptions();

// Set public access policy. Possible values are
// PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
// CONTAINER_AND_BLOBS:
// Specifies full public read access for container and blob data.
// proxys can enumerate blobs within the container via anonymous
// request, but cannot enumerate containers within the storage account.
//
// BLOBS_ONLY:
// Specifies public read access for blobs. Blob data within this
// container can be read via anonymous request, but container data is not
// available. proxys cannot enumerate blobs within the container via
// anonymous request.
// If this value is not specified in the request, container data is
// private to the account owner.
$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

// Set container metadata.
$createContainerOptions->addMetaData("key1", "value1");
$createContainerOptions->addMetaData("key2", "value2");
$containerName = $_POST['container'];
$fileToUpload = $_FILES["fileToUpload"]['tmp_name'];
    try {
        // Create container.
        // $blobClient->createContainer($containerName, $createContainerOptions);

        // Getting local file so that we can upload it to Azure
        $myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
        fclose($myfile);
        
        # Upload file as a block blob
        echo "Uploading BlockBlob: ".PHP_EOL;
        echo $fileToUpload;
        echo "<br />";
        $target_file = basename($_FILES["fileToUpload"]["name"]);
        $content = fopen($fileToUpload, "r");

        //Upload blob
        $blobClient->createBlockBlob($containerName, $target_file, $content);
		header('location:phpQS.php?container='.$containerName);        
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
		echo "<div class='container'><div class='inner_container'>";
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo "<h3 style='color:red'><b>".$code.": ".$error_message."</b></h3>";
		echo '<div class="button_cont" id="btn1"  align="center"><a class="btn red" href="phpQs.php" rel="nofollow noopener">Kembali</a></div>';
		echo "</div></div>";
		
    }
    catch(InvalidArgumentTypeException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
		echo "<div class='container'><div class='inner_container'>";
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo "<h3 style='color:yellow'><b>".$code.": ".$error_message."</b></h3>";
		echo '<div class="button_cont" id="btn1"  align="center"><a class="btn red" href="phpQs.php" rel="nofollow noopener">Kembali</a></div>';
		echo "</div></div>";
    }
?>
</body>
</html>
