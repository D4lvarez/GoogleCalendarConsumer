document.querySelector("form").addEventListener("submit", function (event) {
  event.preventDefault();
  let data = new FormData(this);
  data.append("reason", "insert");
  data.append("token", localStorage.getItem("token_access"));
  data.append("refresh", localStorage.getItem("refresh_token"));
  data.append("calendarId", localStorage.getItem("calendar"));
  for (var value of data.entries()) {
    console.log(value);
  }
  fetch("client/events.php", {
    method: "POST",
    body: data
  })
    .then(response => {
      console.log(response);
      if (response.ok) {
        response.json()
          .then(res => {
            console.log(res);
          })
          .catch(err => {
            console.log(err);
          })
      }
    })
    .catch(err => console.log(err));
});
