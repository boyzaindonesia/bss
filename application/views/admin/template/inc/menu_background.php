<div class="inner-panel">
    <div class="cog-panel" id="demo-panel"><i class="fa fa-cog fa-spin"></i></div>
    <p class="text-muted small text-center">GANTI WARNA</p>

    <form action="" method="post" enctype="multipart/form-data">
        <?php
        $bg    = $this->jCfg['user']['bg'];
        $color = $this->jCfg['user']['color'];
        ?>
        <div class="row text-center">
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg==''&&$color==''?'active':'') ?>" data-toggle="tooltip" title="Default" data-bg="" data-color="">
                    <div class="half-tiles bg-dark"></div>
                    <div class="half-tiles bg-dark"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg=='light'&&$color=='white'?'active':'') ?>" data-toggle="tooltip" title="Light" data-bg="light" data-color="white">
                    <div class="half-tiles bg-white"></div>
                    <div class="half-tiles bg-white"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg==''&&$color=='primary'?'active':'') ?>" data-toggle="tooltip" title="Primary dark" data-bg="" data-color="primary">
                    <div class="half-tiles bg-primary"></div>
                    <div class="half-tiles bg-dark"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg==''&&$color=='info'?'active':'') ?>" data-toggle="tooltip" title="Info dark" data-bg="" data-color="info">
                    <div class="half-tiles bg-info"></div>
                    <div class="half-tiles bg-dark"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg==''&&$color=='success'?'active':'') ?>" data-toggle="tooltip" title="Success dark" data-bg="" data-color="success">
                    <div class="half-tiles bg-success"></div>
                    <div class="half-tiles bg-dark"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg==''&&$color=='danger'?'active':'') ?>" data-toggle="tooltip" title="Danger dark" data-bg="" data-color="danger">
                    <div class="half-tiles bg-danger"></div>
                    <div class="half-tiles bg-dark"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg==''&&$color=='warning'?'active':'') ?>" data-toggle="tooltip" title="Warning dark" data-bg="" data-color="warning">
                    <div class="half-tiles bg-warning"></div>
                    <div class="half-tiles bg-dark"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg=='light'&&$color=='primary'?'active':'') ?>" data-toggle="tooltip" title="Primary light" data-bg="light" data-color="primary">
                    <div class="half-tiles bg-primary"></div>
                    <div class="half-tiles bg-white"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg=='light'&&$color=='info'?'active':'') ?>" data-toggle="tooltip" title="Info light" data-bg="light" data-color="info">
                    <div class="half-tiles bg-info"></div>
                    <div class="half-tiles bg-white"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg=='light'&&$color=='success'?'active':'') ?>" data-toggle="tooltip" title="Success light" data-bg="light" data-color="success">
                    <div class="half-tiles bg-success"></div>
                    <div class="half-tiles bg-white"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg=='light'&&$color=='danger'?'active':'') ?>" data-toggle="tooltip" title="Danger light" data-bg="light" data-color="danger">
                    <div class="half-tiles bg-danger"></div>
                    <div class="half-tiles bg-white"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="xs-tiles change-theme <?php echo ($bg=='light'&&$color=='warning'?'active':'') ?>" data-toggle="tooltip" title="Warning light" data-bg="light" data-color="warning">
                    <div class="half-tiles bg-warning"></div>
                    <div class="half-tiles bg-white"></div>
                </div>
            </div>
        </div>
        <div class="btn btn-block btn-primary btn-sm change-theme" data-bg="" data-color="">Reset to default</div>
    </form>
</div>