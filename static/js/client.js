const getToken = () => {
  const client_id = "561037342448-7698atk72j4ma0jb7gsbr1vdl698q61r.apps.googleusercontent.com";
  const client_secret = "CsoDNyz1KpqU5v6hOgQ-BXnc";
  const redirect_uri = "http%3A//localhost/ibsell/google-calendar/index.html";
  const code = localStorage.getItem("code_auth");
  fetch("https://oauth2.googleapis.com/token", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `code=${code}&client_id=${client_id}&client_secret=${client_secret}&redirect_uri=${redirect_uri}&grant_type=authorization_code`,
  })
    .then(response => {
      console.log("Primer Response")
      console.log(response);
      if (response.ok) {
        response.json()
          .then(res => {
            console.log(res);
            if (localStorage.getItem("load") == 0) {
              localStorage.setItem("load", 1);
              localStorage.setItem("token_access", res.access_token);
              localStorage.setItem("refresh_token", res.refresh_token);
            }
          })
          .catch(err => {
            console.log(err);
          })
      } else {
        response.json()
          .then(res => {
            console.log(res)
          })
      }
    })
    .then(res => {
      console.log(res);
    })
    .catch(err => {
      console.log(err);
    })
}

const getCode = () => {
  let urlParams = window.location.search; // If url no have params is equal to a empty string
  if (urlParams.length !== 0) {
    let params = new URLSearchParams(urlParams);
    let code = params.get("code");
    localStorage.setItem("load", 0); // Set a counter to handle reloads
    if (localStorage.getItem("code_auth") === null) {
      localStorage.setItem("code_auth", code);
    }
  }
}

const refresToken = () => {
  const urlRefresh = "https://oauth2.googleapis.com/token";
  const client_id = "561037342448-7698atk72j4ma0jb7gsbr1vdl698q61r.apps.googleusercontent.com";
  const client_secret = "CsoDNyz1KpqU5v6hOgQ-BXnc";
  const refresh_token = localStorage.getItem("refresh_token");

  fetch(urlRefresh, {
    method: "POST",
    headers: {
      "Content-Type": "x-www-urlencoded"
    },
    body: `client_id=${client_id}&=client_secret=${client_secret}&refresh_token=${refresh_token}&grant_type=refresh_token`
  })
    .then(response => {
      console.log(response);
      if (response.ok) {
        response.json()
          .then(res => {
            console.log(res);
            localStorage.setItem("token_access", res.access_token);
          })
          .catch(err => console.log(err));
      }
    })
    .catch(err => console.log(err));
}

const getCalendarList = () => {
  let formData = new FormData();
  formData.append("token", localStorage.getItem("token_access"));
  formData.append("refresh", localStorage.getItem("refresh_token"));

  fetch("client/client.php", {
    method: "POST",
    body: formData
  })
    .then(response => {
      console.log(response);
      if (response.ok) {
        response.json()
          .then(res => {
            console.log(res);
          })
          .catch(err => console.log(err));
      }
    })
    .catch(err => console.log(err));
}

document.addEventListener("load", getCode());
const buttonToken = document.querySelector("#handleToken");
if (localStorage.getItem("code_auth") !== null) {
  buttonToken.style.display = "block";
  buttonToken.addEventListener("click", getToken);
  document.querySelector("#oauth").style.display = "none";
}

