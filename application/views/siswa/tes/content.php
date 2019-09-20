<div class="content-wrapper">
    <div class="row justify-content-center" style="width: 100%">
        <h3 class="mt-2">Ulangan Akhir Semester Matematika</h3>
    </div>
    <div class="row" style="width: 100%">
        <div class="col-3 ml-5 mt-5">
            <div class="card">
                <div class="card-header">
                    Daftar Soal
                </div>
                <div class="card-body">
                    <div class="row">
                        <button class="btn btn-default">1</button>
                        <button class="btn btn-default">2</button>
                        <button class="btn btn-default">3</button>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-success">Selesai</button>
            </div>
        </div>
        <div class="col-8 mt-5">
            <div class="card">
                <div class="card-header">
                    <div id="no">
                        <h4>1.</h4>
                    </div>
                    <div class="row">
                        <div class="col-12" id="audio">
                            <audio src=""></audio>
                        </div>
                        <div class="col-12">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit sunt laboriosam id quaerat dicta deleniti vel provident, nemo necessitatibus, commodi doloribus unde. Adipisci doloremque officia qui deleniti optio tenetur aliquam.
                        </div>
                    </div>
                </div>
                <div class="card-body" id="option">
                    <?php $opt = ['A', 'B', 'C', 'D', 'E'] ?>
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                        <div class="m-2">
                            <input type="radio" name="jawaban" id=""><?= $opt[$i] ?>. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ratione, nesciunt porro! Ratione deserunt unde quasi maiores laudantium adipisci ab,
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="card-footer row justify-content-center">
                    <button class="btn btn-secondary mr-2">Kembali</button>
                    <button class="btn btn-warning">Ragu-ragu</button>
                    <button class="btn btn-primary ml-2">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>