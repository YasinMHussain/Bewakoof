
function validateForm() {
  const phone = document.getElementById("contactNo").value.trim();
  const email = document.getElementById("email").value.trim();

  const phonePattern = /^[0-9]{10}$/;
  const emailPattern = /^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  if (!phonePattern.test(phone)) {
    alert("Phone number must have exactly 10 digits.");
    return false;
  }

  if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    return false;
  }

  return true;
}