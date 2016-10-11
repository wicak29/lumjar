<body id="" class="index">
    <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Formulir Pendaftaran</h2>
                    <h3 class="section-subheading text-muted">Silahkan mengisi form berikut</h3>
                    <h2 ng-if="res.status == 200">Selamat Registrasi Berhasil !!</h2>
                    <h3 ng-if="res.status == 200" class="section-subheading text-muted">Nama:{{res.Name}}</h3>
                    <h3 ng-if="res.status == 200" class="section-subheading text-muted">PIN:{{res.PIN}}</h3>
                </div>
            </div>
        <form role="form">
<!--             <div class="form-group">
              <h4 class="section-heading">ID</h4>
              <input ng-model="ID" name="id" type="text" class="form-control" style="width:20%" id="id" placeholder="Masukkan Nomor ID">
            </div> -->
            <div class="form-group">
              <h4 class="section-heading">NAMA</h4>
              <input ng-model="data.nama" name="name" type="text" class="form-control" style="width:20%" id="name" placeholder="Masukkan Nama">
            </div>
            <div class="form-group">
                  <h4 class="section-heading">ALAMAT</h4>
              <input ng-model="data.alamat" name="address" type="text" class="form-control" style="width:20%" id="address" placeholder="Masukkan Alamat">
            </div>
            <div class="form-group">
              <h4 class="section-heading">TELEPON</h4>
              <input ng-model="data.telepon" name="phone" type="text" class="form-control" style="width:20%" id="phone" placeholder="Masukkan Nomor Telepon">
            </div>
            <div class="form-group">
              <h4 class="section-heading">USERNAME</h4>
              <input ng-model="data.username" name="user" type="text" class="form-control" style="width:20%" id="user" placeholder="Masukkan Username">
            </div>
            <div class="form-group">
              <h4 class="section-heading">PASSWORD</h4>
              <input ng-model="data.password" name="pass" type="password" class="form-control" style="width:20%" id="pass" placeholder="Masukkan Password">
            </div>
           <button ng-click="submit()" class="page-scroll btn btn-xl">Daftar</a>
           <html lang="en">
        </form>
</body>