<html>
<head>
<title>Submission 2 Microsoft Azure Dicoding</title>

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
.inputfile {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
}
.inputfile + label {
    font-size: 1.25em;
    font-weight: 700;
    color: white;
	border-radius: 5px;
    background-color: black;
    display: inline-block;
	padding:10px;
}

.inputfile:focus + label,
.inputfile + label:hover {
    background-color: red;
}
.inputfile + label {
	cursor: pointer; /* "hand" cursor */
}

table {
  border-collapse: collapse;
}
 th {
  background: #ccc;
}

th, td {
  border: 1px solid #ccc;
  padding: 8px;
}

tr:nth-child(even) {
  background: #efefef;
}

tr:hover {
  background: #d1d1d1;
}
</style>
</head>
<body>
	<div class="container">
		<div class="inner_container">
<?php
/**----------------------------------------------------------------------------------
* Microsoft Developer & Platform Evangelism
*
* Copyright (c) Microsoft Corporation. All rights reserved.
*
* THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
* EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES 
* OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
*----------------------------------------------------------------------------------
* The example companies, organizations, products, domain names,
* e-mail addresses, logos, people, places, and events depicted
* herein are fictitious.  No association with any real company,
* organization, product, domain name, email address, logo, person,
* places, or events is intended or should be inferred.
*----------------------------------------------------------------------------------
**/

/** -------------------------------------------------------------
# Azure Storage Blob Sample - Demonstrate how to use the Blob Storage service. 
# Blob storage stores unstructured data such as text, binary data, documents or media files. 
# Blobs can be accessed from anywhere in the world via HTTP or HTTPS. 
#
# Documentation References: 
#  - Associated Article - https://docs.microsoft.com/en-us/azure/storage/blobs/storage-quickstart-blobs-php 
#  - What is a Storage Account - http://azure.microsoft.com/en-us/documentation/articles/storage-whatis-account/ 
#  - Getting Started with Blobs - https://azure.microsoft.com/en-us/documentation/articles/storage-php-how-to-use-blobs/
#  - Blob Service Concepts - http://msdn.microsoft.com/en-us/library/dd179376.aspx 
#  - Blob Service REST API - http://msdn.microsoft.com/en-us/library/dd135733.aspx 
#  - Blob Service PHP API - https://github.com/Azure/azure-storage-php
#  - Storage Emulator - http://azure.microsoft.com/en-us/documentation/articles/storage-use-emulator/ 
#
**/

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


$listBlobsOptions = new ListBlobsOptions();
//$listBlobsOptions->setPrefix("HelloWorld");
$containerName = "artblobtest";
/*if(isset($_GET['container'])){
	$containerName=$_GET['container'];
}*/
echo "Berikut ini adalah Daftar Berkas yang ada pada Container ".$containerName.": ";
echo "<table width='650px'>";
echo "<tr><th width='5%'>No.</th><th>Name</th><th>URL</th><th>Action</th></tr>";
do{
	$result = $blobClient->listBlobs($containerName, $listBlobsOptions);	
	$counter = 1;
	foreach ($result->getBlobs() as $blob)
	{
		echo "<tr><td>".$counter."</td><td>".$blob->getName()."</td><td>".$blob->getUrl()."</td><td><a href='delete.php?name=".$blob->getName()."&container=".$containerName."' class='btn red'>Hapus</a></tr>";
	}
	
	$listBlobsOptions->setContinuationToken($result->getContinuationToken());
} while($result->getContinuationToken());
echo "</table>";
?>
<hr>
<form action="uploading.php" method="post" enctype="multipart/form-data">
    Pilih Berkas untuk di Upload : <br>
    <input type="file" name="fileToUpload" id="fileToUpload" class="inputfile">
	<label for="fileToUpload">Choose a file</label>
	<input type="hidden" name="container" value="<?php echo $containerName;?>"/>
	<input type="submit" value="Upload File" name="submit" class="btn blue">
</form>
<hr>
<!--<form method="post" action="cleaning.php?Cleanup&containerName=<?php echo $containerName; ?>">
    <button class="btn red" type="submit">Bersihkan isi Container</button>
</form>-->
<a href="index.php" class="btn red">Kembali</a>
</div>
</div>
</body>
</html>

<?php
/*
$fileToUpload = "HelloWorld.txt";

if (!isset($_GET["Cleanup"])) {
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

      $containerName = "blockblobs".generateRandomString();

    try {
        // Create container.
        $blobClient->createContainer($containerName, $createContainerOptions);

        // Getting local file so that we can upload it to Azure
        $myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
        fclose($myfile);
        
        # Upload file as a block blob
        echo "Uploading BlockBlob: ".PHP_EOL;
        echo $fileToUpload;
        echo "<br />";
        
        $content = fopen($fileToUpload, "r");

        //Upload blob
        $blobClient->createBlockBlob($containerName, $fileToUpload, $content);

        // List blobs.
        $listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix("HelloWorld");

        echo "These are the blobs present in the container: ";

        do{
            $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
            foreach ($result->getBlobs() as $blob)
            {
                echo $blob->getName().": ".$blob->getUrl()."<br />";
            }
        
            $listBlobsOptions->setContinuationToken($result->getContinuationToken());
        } while($result->getContinuationToken());
        echo "<br />";

        // Get blob.
        echo "This is the content of the blob uploaded: ";
        $blob = $blobClient->getBlob($containerName, $fileToUpload);
        fpassthru($blob->getContentStream());
        echo "<br />";
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
    catch(InvalidArgumentTypeException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
}else 
{

    try{
        // Delete container.
        echo "Deleting Container".PHP_EOL;
        echo $_GET["containerName"].PHP_EOL;
        echo "<br />";
        $blobClient->deleteContainer($_GET["containerName"]);
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
}*/
?>