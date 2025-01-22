<style nonce="{{ csp_nonce() }}">
    #systemLoader{
        background-color: rgba(0,0,0,0.5);
        position: fixed;
        height: 100vh;
        z-index: 99999999;
    }
    .systemLoader {
  width: 100px;
  height: 100px;
  border: 7px double;
  border-color: #f66b04 transparent;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  animation: spin13213 2s linear infinite;
}

.systemLoader div {
  width: 50%;
  height: 50%;
  background-color: #f66b04;
  border-radius: 50%;
}

@keyframes spin13213 {
  to {
    transform: rotate(360deg);
  }
}
</style>
<div id="systemLoader" class="d-none  justify-content-center align-items-center top-0 left-0 w-100">
    <div class="systemLoader">
        <div></div>
    </div>
</div>
