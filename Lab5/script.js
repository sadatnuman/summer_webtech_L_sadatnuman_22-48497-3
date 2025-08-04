document.getElementById("myForm").addEventListener("submit", function (event) {
  const name = document.getElementById("fullName").value.trim();
  const email = document.getElementById("email").value.trim();
  const age = document.getElementById("age").value;

  if (name === "" || !/^[A-Za-z\s]+$/.test(name)) {
    alert("Please enter a valid name.");
    event.preventDefault();
    return;
  }

  if (email === "" || !email.includes("@") || !email.includes(".")) {
    alert("Please enter a valid email.");
    event.preventDefault();
    return;
  }

  if (age === "" || isNaN(age) || age < 10 || age > 100) {
    alert("Please enter a valid age between 10 and 100.");
    event.preventDefault();
    return;
  }
});
