<script>
document.querySelector("form").addEventListener("submit", function(e) {
    let pwd = document.querySelector("[name=password]").value;
    let cpwd = document.querySelector("[name=cpassword]").value;
    if(pwd !== cpwd) {
        alert("Passwords do not match!");
        e.preventDefault();
    }
});
</script>
