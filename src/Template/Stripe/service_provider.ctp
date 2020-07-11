<style>
    /* Buttons and links */
    button {
        cursor: pointer;
        display: inline-block;
        background:url('<?= SITE_URL."blue-on-dark.png" ?>') !important;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        border-width: 0;
        height: 33px;
        width:190px !important;
    }
    button:hover {
        filter: contrast(115%);
    }
    button:active {
        transform: translateY(0px) scale(0.98);
    }
    button:disabled {
        opacity: 0.5;
        cursor: none;
    }
    button:focus {
        box-shadow: none;
    }
</style>

<div class="row mt-5">
    <div class="col-6 col-md-4 offset-md-4 offset-3">
        <div class="w-100 text-center">
            <h3 class="display-4">Yashwant Singh</h3>
            <p class="lead">Service Provider Panel</p>
        </div>
        <div class='card mt-5'>
            <div class='card-body text-center'>
                <button id="submit"></button><br />
                Are you a Customer? <a href="<?= SITE_URL?>stripe/do_payment">Customer</a>
            </div>
        </div>  
    </div>
</div>      

<script>
    let elmButton = document.querySelector("#submit");

    if (elmButton) {
        elmButton.addEventListener(
            "click",
            e => {
            elmButton.setAttribute("disabled", "disabled");

            fetch("<?= SITE_URL ?>Api/get-oauth-link", {
                method: "GET",
                headers: {
                "Content-Type": "application/json"
                }
            })
                .then(response => response.json())
                .then(data => {
                if (data.url) {
                    window.location = data.url;
                } else {
                    elmButton.removeAttribute("disabled");
                    elmButton.textContent = "<Something went wrong>";
                    console.log("data", data);
                }
                });
            },
            false
        );
    }
</script>