window.onload = () => {
  const fileInput = document.getElementById("image");
  const imagePreview = document.getElementById("image-preview");

  if(fileInput) fileInput.addEventListener("change", (e) => {
    const [file] = e.target.files;
    if(file) {
      const imgObj = document.createElement("img");
      imgObj.src = URL.createObjectURL(file);
      imgObj.width = 250;
      
      imagePreview.innerHTML = "";
      imagePreview.append(imgObj);
    }
  })

  const button = document.querySelectorAll(".orders button");

  for(let i = 0; i < button.length; i++) {
    button[i].addEventListener("click", (e) => {
      let parent = e.target.parentElement;
      parent.setAttribute("data-state", parent.getAttribute("data-state") == "close" ? "open" : "close")
    })
  }
}