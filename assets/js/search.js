const searchbar = document.getElementById('searchField');

searchbar.addEventListener("keyup", fetchResult);

function fetchResult(e) {
    let target = e.target.value;

    if (target !== "") {

        let http = new XMLHttpRequest();
        http.open("POST", "includes/handlers/search-course.php", true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onload = () => {
            let coursesFound = JSON.parse(http.responseText);

            if (coursesFound.length > 0) {
                let courses = [];

                coursesFound.forEach(course => {
                    let title = { title: course.title, url: `details.php?id=${course.id}` }
                    courses.push(title);
                });

                $('.ui.search').search({
                    source: courses
                });
            }
        }
        let data = "search-value=" + target;
        http.send(data);
    }
}