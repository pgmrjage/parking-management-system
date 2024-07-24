<?php
include("config.php");

// Check if the user ID and control number are provided in the URL parameters
if (isset($_GET['userId']) && isset($_GET['controlNumber'])) {
    $userId = $_GET['userId'];
    $controlNumber = $_GET['controlNumber'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Upload Images</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

            body {
                font-family: 'Poppins';
                background-color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            h2 {
                color: #fff;
                font-size: 24px;
                font-weight: 800;   
                margin:0px;
                font-family: "Poppins", sans-serif;
            }

            .header{
                display: flex;
                justify-content: space-between;
                align-content: center;
                align-items: center;
            }

            .header-button a {
                
                margin-right: 5px; /* Adjust spacing between icon and text */
                color: #fff;   /* Adjust icon color */
                font-size: 24px;  /* Adjust icon size */

            }

            .upload-container
            {
                display: grid;
                padding: 20px;
                justify-content: space-evenly;
                align-items: center;
                border-radius: 8px;
                background-color: #00843D;
                width: auto;
            }
            .upload-details{
                display: flex;
                justify-content: space-between;
                padding: 10px;
            }

            .upload-details, a{
                text-align: center;
                align-items: center;
                color: #fff;
                font-size: 24px;
            }

            form {
                display: grid;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                /* background-color: #fff; */
                /* border-radius: 5px;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); */
            }

            input[type="file"] {
                margin-bottom: 10px;
            }

            button[type="submit"] {
                margin-top: 20px;
                padding: 10px 20px;
                border: none;
                background-color: #007bff;
                color: #fff;
                font-size: 16px;
                cursor: pointer;
                border-radius: 5px;
                font-family: 'Poppins';
            }

            button[type="submit"]:hover {
                background-color: #0056b3;
            }

            #preview {
                display: flex;
                flex-wrap: wrap;
                margin: 14px;
                
            }

            #preview img {
                /* display: flex; */
                margin: 12px;
                max-width: 100px;
                /* margin-right: 10px;
                margin-bottom: 10px; */
            }


            /*  */
            .custum-file-upload {
  height: 200px;
  width: 300px;
  display: flex;
  flex-direction: column;
  align-items: space-between;
  gap: 20px;
  cursor: pointer;
  align-items: center;
  justify-content: center;
  border: 2px dashed #cacaca;
  background-color: rgba(255, 255, 255, 1);
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0px 48px 35px -48px rgba(0,0,0,0.1);
}

.custum-file-upload .icon {
  display: flex;
  align-items: center;
  justify-content: center;
}

.custum-file-upload .icon svg {
  height: 80px;
  fill: rgba(75, 85, 99, 1);
}

.custum-file-upload .text {
  display: flex;
  align-items: center;
  justify-content: center;
}

.custum-file-upload .text span {
  font-weight: 400;
  color: rgba(75, 85, 99, 1);
}

.custum-file-upload input {
  display: none;
}



/* UPLOAD CSS */
.file-upload-form {
  width: fit-content;
  height: fit-content;
  display: grid;
  align-items: center;
  justify-content: center;
}
.file-upload-label input {
  display: none;
}
.file-upload-label svg {
  height: 50px;
  fill: #00843D;
  /* margin-bottom: 20px; */
}
.file-upload-label {
  cursor: pointer;
  background-color: #fff;
  /* padding: 30px 70px; */
  height:50vh;
  width: 80vh;
  border-radius: 20px;
  border: 2px dashed rgb(82, 82, 82);
  box-shadow: 0px 0px 200px -50px rgba(0, 0, 0, 0.719);
  text-align: center;
  align-content: center;
}
.file-upload-design {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  /* gap: 5px; */
}
.browse-button {
    display: none;
  background-color: #fff;
  padding: 5px 15px;
  border-radius: 10px;
  color: #333;
  transition: all 0.3s;
}
.browse-button:hover {
  background-color: rgb(14, 14, 14);
}


/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7);
}

.modal-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    padding: 20px;
    margin-top: 50px;
}

.modal-content img {
    max-width: 100%;
    max-height: 80vh;
    margin: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.close {
    color: #fff;
    font-size: 30px;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
}


        </style>
    </head>

    <body>

    
       
        <!-- <div class="upload-container">
                <div class="upload-details"><h2>Update <br> Images</h2></div>
                <div class="upload-details">
                <form id="uploadForm" class="file-upload-form" action="process_upload.php" method="post" enctype="multipart/form-data">
                    <label for="file" class="file-upload-form">
                        <div class="file-upload-design">
                            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                            <input type="hidden" name="controlNumber" value="<?php echo $controlNumber; ?>">
                        </div>

                    
                    </label>
                    
                    <div id="preview"></div>
                    <input type="file" name="uploadedImages[]" multiple accept="image/*" id="fileInput">
                    <button type="submit" name="uploadImages" href="index.php">Upload</button>
                </form>
                </div>
        </div> -->

            <!-- FIX THE AREA BELOW -->


            <div class="upload-container">
    <div class="upload-details">
        <a href="index.php"><i class="fas fa-arrow-left"></i></a>
        <h2>Update Images</h2>
    </div>
    <form class="file-upload-form" id="uploadForm" action="process_upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
        <input type="hidden" name="controlNumber" value="<?php echo $controlNumber; ?>">
        
        <label for="fileInput" class="file-upload-label">
            <div class="file-upload-design">
                <svg viewBox="0 0 640 512" height="1em">
                    <path d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39V392c0 13.3 10.7 24 24 24s24-10.7 24-24V257.9l39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z"></path>
                </svg>
                <p>Click to Browse File</p>
                <span class="browse-button">Browse file</span>
            </div>
            <input type="file" name="uploadedImages[]" multiple accept="image/*" id="fileInput">
        </label>
        
        <button type="submit" name="uploadImages" href="index.php">Upload</button>
    </form>

    <div id="previewModal" class="modal">
        <span class="close" onclick="closePreviewModal()">&times;</span>
        <div class="modal-content" id="previewContent"></div>
    </div>
</div>

            

        

        <script>
            document.getElementById('fileInput').addEventListener('change', function () {
                let preview = document.getElementById('preview');
                preview.innerHTML = '';
                let files = this.files;
                for (let i = 0; i < files.length; i++) {
                    let file = files[i];
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>

        
<script>
    // Function to open the modal with previewed images
function openPreviewModal() {
    document.getElementById('previewModal').style.display = 'block';
}

// Function to close the modal
function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
}

// Function to handle file selection and display preview
document.getElementById('fileInput').addEventListener('change', function() {
    var previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = ''; // Clear previous content

    var files = this.files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            previewContent.appendChild(img);
        }

        reader.readAsDataURL(file);
    }

    openPreviewModal(); // Open the modal after loading images
});

</script>


<!-- 
        <script>
    document.getElementById('fileInput').addEventListener('change', function() {
        // Get the file input element
        // var fileInput = event.target;

        // Ensure files were selected
        if (fileInput.files.length > 0) {
            // Get the first selected file
            var file = fileInput.files[0];

            // Create a FileReader object
            var reader = new FileReader();

            // Set up the FileReader onload function
            reader.onload = function(e) {
                // Create a new image element
                var imgElement = document.createElement('img');

                // Set the source of the image to the loaded data URL
                imgElement.src = e.target.result;

                // Append the image element to the preview container
                var previewContainer = document.getElementById('preview');
                previewContainer.innerHTML = ''; // Clear previous preview (if any)
                previewContainer.appendChild(imgElement);
            };

            // Read the selected file as a data URL
            reader.readAsDataURL(file);
        }
    }); -->
</script>

    </body>

    </html>

    <?php

} else {
    echo "Invalid request. User ID and control number not provided.";
}
?>