
document.body.onload = () => {
  fetch("php-api/users/getdbinfo")
    .then(console.log)
    .catch(console.log)
}