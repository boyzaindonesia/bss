<?php include('header.php');?>

<style type="text/css">
    .error-number {
        color: #000;
        text-align: center;
        margin: 20px auto;
        padding: 0px;
    }
    .error-number h1 {
        background-color: #8cc63f;
        color: #fff;
        font-size: 80px;
        letter-spacing: 10px;
        text-align: center;
        font-weight: 700;
        width: 200px;
        height: 200px;
        line-height: 200px;
        margin: 20px auto;
        padding: 0px;
        display: inline-block;
    }
    .error-number h4,
    .error-number p {
        color: #000;
    }
</style>
<div id="main">
    <div class="section">
        <div class="container">
            <div class="error-number">
                <h1 class="not-animated" data-animate="fadeInUp">404</h1>
                <h4 class="not-animated" data-animate="fadeInUp" data-delay="400">Halaman tidak ditemukan</h4>
                <p><a href="<?php echo base_url() ?>" class="not-animated" data-animate="fadeInUp" data-delay="800">Back to homepage</a></p>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php');?>