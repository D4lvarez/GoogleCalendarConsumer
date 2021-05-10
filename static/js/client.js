const url = window.location.search;
const urlParams = new URLSearchParams(url);

const auth_token = urlParams.get("code");
const client_id =
  "561037342448-7698atk72j4ma0jb7gsbr1vdl698q61r.apps.googleusercontent.com";
const client_secret = "CsoDNyz1KpqU5v6hOgQ-BXnc";
const redirect_uri = "http%3A//localhost/ibsell/google-calendar/client.html";

fetch("https://oauth2.googleapis.com/token", {
  method: "POST",
  headers: {
    "Content-Type": "application/x-www-form-urlencoded",
  },
  body: `code=${auth_token}&client_id=${client_id}&client_secret=${client_secret}&redirect_uri=${redirect_uri}&grant_type=authorization_code`,
})
  .then((response) => {
    response
      .json()
      .then((res) => {
        console.log(res.access_token);
        if (localStorage.getItem("access_token").length === 0) {
          localStorage.setItem("access_token", res.access_token);
          localStorage.setItem("refresh_token", res.refresh_token);
        }
      })
      .catch((err) => {
        console.log(err);
      });
  })
  .catch((error) => {
    console.log(error);
  });

function refreshToken() {
  let refresh = JSON.stringify(localStorage.getItem("refresh_token"));
  fetch("https://oauth2.googleapis.com/token", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `client_id=${client_id}&client_secret=${client_secret}&refresh_token=${refresh}&grant_type=refresh_token`,
  })
    .then((response) => {
      response
        .json()
        .then((res) => {
          localStorage.setItem("access_token", res.access_token);
        })
        .catch((err) => {
          console.log(err);
        });
    })
    .catch((error) => {
      console.log(error);
    });

  setTimeout(refreshToken, 3599000);
}

refreshToken();

const button = document.querySelector("#getEvents");
const secction = document.querySelector("#events");
const data = new FormData();
data.append(
  "access_token",
  JSON.stringify(localStorage.getItem("access_token"))
);
console.log(localStorage.getItem("access_token"));
button.addEventListener("click", async function () {
  let response = await fetch("./client/client.php", {
    method: "POST",
    body: data,
  });

  response
    .json()
    .then((res) => {
      console.log(res.data);
      secction.innerHTML = res.data.kind;
    })
    .catch((err) => {
      console.log(err);
    });
});
