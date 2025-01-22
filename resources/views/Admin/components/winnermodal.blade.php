<style nonce="{{ csp_nonce() }}">
    .winner{
        z-index: 999999999; background-color: rgba(0,0,0,0.4); width: 100vw !important; left: 0
    }
    #canvas{
        overflow-y: hidden;
  overflow-x: hidden;
  width: 100%;
  height:100%;
  position: fixed;
  margin: 0;
    }
</style>
<div id="confetti" class="left-0 top-0 d-none position-fixed w-100 h-100 justify-content-center align-items-center">
<canvas id="canvas"></canvas>

<div class="winner-container">
    <h1>🎉 Congratulations to the Winner! 🎉</h1>
    <div class="winner-card">
        <div class="winner-info">
            <h2> <span id="cluster-winner"></span> Cluster Winner</h2>
            <h2 id="winner-name">User Name</h2>
            <p class="fs-3"><strong>Serial No: </strong><span id="serial-number-winner"></span></p>
            <p class="fs-3 mb-1"><strong>Retail Station: </strong><span id="retail-station"></span></p>
            <p class="fs-3 mb-1"><strong>Distributor: </strong><span id="distributor"></span></p>
            <p class="fs-3"><strong>Product Purchased: </strong><span id="product-purchased-winner"></span></p>
        </div>

    </div>
</div>

</div>
