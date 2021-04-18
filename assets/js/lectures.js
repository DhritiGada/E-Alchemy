let videoLinks = document.querySelectorAll('a.item');

videoLinks.forEach(videoLink => {
    videoLink.addEventListener('click', changeVideo)
});

function changeVideo(e) {
    let courseName = document.getElementById('courseName').textContent;
    let videoPlayer = document.getElementById('video');
    let videoTitle = e.target.textContent;

    // Change source of video
    videoPlayer.setAttribute("src", `assets/courses/${courseName}/` + videoTitle + ".mp4")
}