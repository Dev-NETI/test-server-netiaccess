<section class="pt-md-14 pt-5 pb-14">
    <div class="container">
        <div class="row">
            <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12">
                <div class="mb-4 mb-xl-0 text-center">
                    <h1 class="display-2 ls-sm mt-2 fw-bold">Request Access
                        for Handout </h1>
                    <p class="mb-6 h2 text-muted px-md-8">
                        Gain exclusive access to valuable course materials with our 'Request Access for Handout'
                        feature. Simply enter your email and handout password. Our streamlined process ensures a quick
                        and hassle-free experience.
                    </p>
                    <form class="row px-lg-16 px-md-14">
                        <div class="mb-3 col-md-8 col-12 ps-md-0">
                            <input type="email" class="form-control" placeholder="Email address" wire:model.defer="email_address">
                            <input type="password" class="form-control mt-2" placeholder="Handout Password" wire:model.defer="handout_password">
                        </div>
                        <div class="d-grid mb-3 col-md-2 col-12 ps-md-0">
                            <button class="btn btn-dark" wire:click.prevent="verifyHandoutPassword">Request Access</button>
                        </div>
                        <div class="d-grid mb-3 col-md-2 col-12 ps-md-0">
                            <button class="btn btn-success" wire:click.prevent="downloadPDF">Download Handout</button>
                        </div>
                        <div class="text-start col-12 fw-medium ps-lg-0">Make sure your email are correct and handout
                            password.
                        </div>
                    </form>
                    <br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</section>