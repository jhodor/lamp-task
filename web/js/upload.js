function updateLabel() {
    const fileInput = document.getElementById("fileToUpload");
    const label = document.querySelector(".file-label");

    if (fileInput.files.length > 0) {
        label.textContent = 'Uploading ' + fileInput.files[0].name + ', please wait...';
        // Automatically submit the form when a file is selected
        document.forms["uploadForm"].submit();
    } else {
        label.textContent = "Choose a file";
    }
}
