<!DOCTYPE html>
<html>
<head>
    <title>Cek Ongkir</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .result-item { padding: 10px; border: 1px solid #ddd; margin: 5px 0; border-radius: 4px; }
        .result-item:hover { background: #f5f5f5; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Cek Ongkos Kirim</h2>
    <p><a href="/cek-ongkir">Buka halaman cek ongkir terbaru</a></p>

    <div class="form-group">
        <label for="search">Cari Kota Tujuan</label>
        <input type="text" id="search" placeholder="Ketik minimal 3 huruf (contoh: jakarta, depok, bandung)">
        <div id="search_results"></div>
        <input type="hidden" id="destination" value="">
        <span id="selected_label" style="font-size:12px;color:#666;"></span>
    </div>

    <div class="form-group">
        <label for="weight">Berat (gram)</label>
        <input type="number" id="weight" placeholder="1000" min="1" value="1000">
    </div>

    <div class="form-group">
        <label for="courier">Kurir</label>
        <select id="courier">
            <option value="">Pilih Kurir</option>
            <option value="jne">JNE</option>
            <option value="tiki">TIKI</option>
            <option value="pos">POS Indonesia</option>
        </select>
    </div>

    <button onclick="cekOngkir()">Cek Ongkir</button>

    <div id="result"></div>

    <script>
        let searchTimer;

        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                let search = this.value.trim();
                if (search.length < 3) {
                    document.getElementById('search_results').innerHTML = '<div style="font-size:12px;color:#666;">Minimal 3 karakter</div>';
                    return;
                }

                fetch(`/ongkir/get-destination?search=${encodeURIComponent(search)}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        if (data.meta && data.meta.code === 200 && data.data && data.data.length > 0) {
                            data.data.forEach(item => {
                                html += `<div class="result-item" onclick="pilih('${item.id}', '${item.label.replace(/'/g, "\\'")}')">${item.label}</div>`;
                            });
                        } else {
                            html = '<div style="font-size:12px;color:#666;">Tidak ada hasil</div>';
                        }
                        document.getElementById('search_results').innerHTML = html;
                    })
                    .catch(err => {
                        document.getElementById('search_results').innerHTML = '<div style="font-size:12px;color:red;">Error: ' + err.message + '</div>';
                    });
            }, 500);
        });

        function pilih(id, label) {
            document.getElementById('destination').value = id;
            document.getElementById('selected_label').textContent = 'Dipilih: ' + label;
            document.getElementById('search_results').innerHTML = '';
            document.getElementById('search').value = label;
        }

        function cekOngkir() {
            let destination = document.getElementById('destination').value;
            let weight = document.getElementById('weight').value;
            let courier = document.getElementById('courier').value;

            if (!destination || !weight || !courier) {
                alert('Harap lengkapi semua field');
                return;
            }

            let formData = new URLSearchParams();
            formData.append('origin', '25986');
            formData.append('destination', destination);
            formData.append('weight', weight);
            formData.append('courier', courier);

            fetch('/ongkir/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData.toString()
            })
            .then(res => res.json())
            .then(data => {
                let resultDiv = document.getElementById('result');
                if (data.meta && data.meta.code === 200 && data.data && data.data.length > 0) {
                    let html = '<h3>Hasil Ongkos Kirim</h3><table><tr><th>Kurir</th><th>Layanan</th><th>Biaya</th><th>Estimasi</th></tr>';
                    data.data.forEach(item => {
                        html += `<tr>
                            <td>${item.name}</td>
                            <td>${item.service}</td>
                            <td>Rp ${Number(item.cost).toLocaleString('id-ID')}</td>
                            <td>${item.etd}</td>
                        </tr>`;
                    });
                    html += '</table>';
                    resultDiv.innerHTML = html;
                } else {
                    resultDiv.innerHTML = '<p>Tidak ada layanan tersedia</p>';
                }
            })
            .catch(err => {
                document.getElementById('result').innerHTML = '<p>Error: ' + err.message + '</p>';
            });
        }
    </script>
</body>
</html>
