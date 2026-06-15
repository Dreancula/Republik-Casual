<!DOCTYPE html>
<html>

<head>
    <title>Cek Ongkir</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }

        .result-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin: 5px 0;
            border-radius: 4px;
        }

        .result-item:hover {
            background: #f5f5f5;
            cursor: pointer;
        }

        .selected {
            background: #e3f2fd;
            border-color: #2196F3;
        }

        #result {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f2f2f2;
        }

        .search-hint {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }
    </style>
</head>

<body>
    <h2>Cek Ongkos Kirim</h2>

    <div class="form-group">
        <label for="origin">Asal Kota (Origin)</label>
        <input type="text" id="origin_search" placeholder="Cari kota asal (min 3 huruf)">
        <div id="origin_results"></div>
        <input type="hidden" id="origin" value="">
        <span id="origin_label" style="font-size:12px;color:#666;"></span>
    </div>

    <div class="form-group">
        <label for="destination_search">Kota Tujuan (Destination)</label>
        <input type="text" id="destination_search" placeholder="Cari kota tujuan (min 3 huruf)">
        <div id="destination_results"></div>
        <input type="hidden" id="destination" value="">
        <span id="destination_label" style="font-size:12px;color:#666;"></span>
    </div>

    <div class="form-group">
        <label for="weight">Berat (gram)</label>
        <input type="number" id="weight" placeholder="1000" min="1">
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
        let originTimer, destTimer;

        function searchDestination(field, resultId, hiddenId, labelId) {
            let input = document.getElementById(field);
            let search = input.value.trim();

            if (search.length < 3) {
                document.getElementById(resultId).innerHTML = '<div class="search-hint">Minimal 3 karakter</div>';
                return;
            }

            fetch(`/ongkir/get-destination?search=${encodeURIComponent(search)}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.meta && data.meta.code === 200 && data.data && data.data.length > 0) {
                        data.data.forEach(item => {
                            html += `<div class="result-item" onclick="selectDestination('${hiddenId}', '${labelId}', '${resultId}', '${item.id}', '${item.label.replace(/'/g, "\\'")}')">
                                ${item.label}
                            </div>`;
                        });
                    } else {
                        html = '<div class="search-hint">Tidak ada hasil ditemukan</div>';
                    }
                    document.getElementById(resultId).innerHTML = html;
                })
                .catch(err => {
                    document.getElementById(resultId).innerHTML = '<div class="search-hint">Error: ' + err.message +
                        '</div>';
                });
        }

        function selectDestination(hiddenId, labelId, resultId, id, label) {
            document.getElementById(hiddenId).value = id;
            document.getElementById(labelId).textContent = 'Dipilih: ' + label;
            document.getElementById(resultId).innerHTML = '';
        }

        document.getElementById('origin_search').addEventListener('input', function() {
            clearTimeout(originTimer);
            originTimer = setTimeout(() => searchDestination('origin_search', 'origin_results', 'origin',
                'origin_label'), 500);
        });

        document.getElementById('destination_search').addEventListener('input', function() {
            clearTimeout(destTimer);
            destTimer = setTimeout(() => searchDestination('destination_search', 'destination_results',
                'destination', 'destination_label'), 500);
        });

        function cekOngkir() {
            let origin = document.getElementById('origin').value;
            let destination = document.getElementById('destination').value;
            let weight = document.getElementById('weight').value;
            let courier = document.getElementById('courier').value;

            if (!origin || !destination || !weight || !courier) {
                alert('Harap lengkapi semua field');
                return;
            }

            let formData = new URLSearchParams();
            formData.append('origin', origin);
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
                        let html =
                            '<h3>Hasil Ongkos Kirim</h3><table><tr><th>Kurir</th><th>Layanan</th><th>Biaya</th><th>Estimasi</th></tr>';
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
