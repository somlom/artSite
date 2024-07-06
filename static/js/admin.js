function confirmDeletion(postId, postTitle) {
  var userInput = prompt("To confirm deletion, please type the post title:");
  if (userInput === postTitle) {
    window.location.href = "admin.php?delete=" + postId;
  } else {
    alert("Post title does not match. Deletion cancelled.");
  }
}

function previewImage(input) {
  var preview = document.getElementById("imagePreview");
  var removeButton = document.getElementById("removeImage");

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = "block";
      removeButton.style.display = "block";
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function removeImage() {
  var preview = document.getElementById("imagePreview");
  var inputFile = document.getElementById("image");
  var removeButton = document.getElementById("removeImage");

  preview.src = "";
  preview.style.display = "none";
  removeButton.style.display = "none";
  inputFile.value = "";
}
