<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tangkis Monitoring</title>
    <meta http-equiv="refresh" content="30">
    <style>
        :root{
            --bg:#f5f7fb;--card:#fff;--text:#152238;--muted:#718096;--line:#e4e9f1;
            --dark:#122033;--blue:#2563eb;--green:#15803d;--amber:#b45309;--red:#b91c1c;
            --shadow:0 10px 30px rgba(17,31,52,.07);
        }
        *{box-sizing:border-box} body{margin:0;background:var(--bg);color:var(--text);
        font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
        .wrap{max-width:1500px;margin:auto;padding:28px}.top{display:flex;justify-content:space-between;
        align-items:center;gap:16px;margin-bottom:22px}.title h1{margin:0;font-size:29px;letter-spacing:-.03em}
        .title p{margin:6px 0 0;color:var(--muted)} .status{background:#ecfdf5;color:var(--green);
        border-radius:999px;padding:9px 13px;font-weight:800;font-size:13px}
        .stats{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:18px}
        .stat,.card{background:var(--card);border:1px solid var(--line);border-radius:18px;box-shadow:var(--shadow)}
        .stat{padding:18px}.stat .label{font-size:13px;color:var(--muted)}.stat .num{font-size:30px;
        font-weight:850;margin-top:7px}.card{overflow:hidden}.head{padding:18px 20px;border-bottom:1px solid var(--line);
        display:flex;justify-content:space-between;align-items:center;gap:15px}.head h2{margin:0;font-size:18px}
        .filters{padding:15px 20px;border-bottom:1px solid var(--line);display:flex;gap:10px;flex-wrap:wrap;align-items:end}
        .field{min-width:210px;display:flex;flex-direction:column;gap:6px}.field label{font-size:12px;color:var(--muted);font-weight:800}
        select,.btn{height:40px;border:1px solid #d5deea;border-radius:10px;background:#fff;padding:0 12px;font:inherit}
        .btn{background:var(--dark);color:#fff;border-color:var(--dark);font-weight:800;cursor:pointer;text-decoration:none;
        display:inline-flex;align-items:center;justify-content:center}.btn.light{background:#fff;color:var(--dark);border-color:#d5deea}
        .json-table{width:100%;border-collapse:collapse;min-width:1250px}.table-scroll{overflow:auto}
        th,td{padding:14px 15px;border-bottom:1px solid var(--line);text-align:left;vertical-align:top}
        th{font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);background:#fbfcfe}
        td{font-size:13px}.row:hover{background:#fafcff}.id{font-weight:850}.muted{color:var(--muted)}
        .badge{display:inline-flex;border-radius:999px;padding:5px 8px;font-size:11px;font-weight:850}
        .normal{background:#ecfdf5;color:var(--green)}.promo{background:#fff7ed;color:var(--amber)}
        .penipuan{background:#fef2f2;color:var(--red)}.mini{font-size:11px;color:var(--muted);margin-top:4px}
        .kv{display:grid;grid-template-columns:135px 1fr;gap:5px 10px;line-height:1.45}
        .kv .k{color:var(--muted)}.msg{max-width:330px;white-space:pre-wrap;line-height:1.45}
        .meta{padding:13px 20px;display:flex;gap:18px;flex-wrap:wrap;color:var(--muted);font-size:12px}
        .pagination{padding:15px 20px;display:flex;justify-content:space-between;gap:15px;align-items:center}
        .pagination nav{display:flex;gap:5px}.pagination a,.pagination span{border:1px solid #d5deea;padding:6px 10px;
        border-radius:8px;color:var(--dark);text-decoration:none;font-size:12px;background:#fff}
        .pagination .active span{background:var(--dark);color:#fff;border-color:var(--dark)}
        .modal{position:fixed;inset:0;background:rgba(11,20,34,.58);display:none;align-items:center;justify-content:center;
        padding:18px;z-index:30}.modal.open{display:flex}.modal-box{width:min(1050px,100%);max-height:92vh;overflow:auto;
        background:#fff;border-radius:20px;box-shadow:0 30px 90px rgba(0,0,0,.28)}
        .modal-head{padding:17px 20px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
        .modal-body{padding:20px}.sections{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
        .section{border:1px solid var(--line);border-radius:14px;padding:15px}.section.full{grid-column:1/-1}
        .section h3{margin:0 0 11px;font-size:14px}.raw{background:#0f172a;color:#dbeafe;border-radius:14px;padding:15px;
        overflow:auto;font-size:12px;line-height:1.55}.danger{display:flex;gap:6px;flex-wrap:wrap}
        @media(max-width:1000px){.stats{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:700px){.wrap{padding:15px}.top{flex-direction:column;align-items:flex-start}.stats{grid-template-columns:repeat(2,1fr)}
        .sections{grid-template-columns:1fr}.section.full{grid-column:auto}}
    </style>
</head>
<body>
@php
    $rows = $detections->items();
@endphp

<div class="wrap">
    <div class="top">
        <div class="title">
            <h1>Tangkis Monitoring</h1>
            <p>Visualisasi response JSON staging dalam bentuk dashboard web.</p>
        </div>
        <div class="status">● Server monitoring</div>
    </div>

    <div class="stats">
        <div class="stat"><div class="label">Total Deteksi</div><div class="num">{{ $stats['total'] }}</div></div>
        <div class="stat"><div class="label">NORMAL</div><div class="num">{{ $stats['normal'] }}</div></div>
        <div class="stat"><div class="label">PROMO</div><div class="num">{{ $stats['promo'] }}</div></div>
        <div class="stat"><div class="label">PENIPUAN</div><div class="num">{{ $stats['penipuan'] }}</div></div>
        <div class="stat"><div class="label">Perangkat</div><div class="num">{{ $stats['devices'] }}</div></div>
    </div>

    <div class="card">
        <div class="head">
            <h2>Data Deteksi</h2>
            <a class="btn light" href="{{ route('monitoring.index') }}">Reset</a>
        </div>

        <form class="filters" method="GET" action="{{ route('monitoring.index') }}">
            <div class="field">
                <label>KATEGORI</label>
                <select name="kategori">
                    <option value="">Semua</option>
                    @foreach($categories as $item)
                        <option value="{{ $item }}" @selected($kategori === $item)>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>PERANGKAT</label>
                <select name="device_id">
                    <option value="">Semua</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" @selected($deviceId === (string)$device->id)>
                            {{ $device->manufacturer }} {{ $device->model }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="btn" type="submit">Terapkan Filter</button>
        </form>

        <div class="meta">
            <span><strong>meta.count:</strong> {{ $meta['count'] }}</span>
            <span><strong>meta.limit:</strong> {{ $meta['limit'] }}</span>
            <span><strong>meta.current_page:</strong> {{ $meta['current_page'] }}</span>
            <span><strong>meta.total_pages:</strong> {{ $meta['total_pages'] }}</span>
            <span><strong>meta.server_time:</strong> {{ $meta['server_time'] }}</span>
        </div>

        <div class="table-scroll">
            <table class="json-table">
                <thead>
                <tr>
                    <th>id</th>
                    <th>pesan</th>
                    <th>hasil_deteksi</th>
                    <th>waktu_deteksi</th>
                    <th>created_at</th>
                    <th>perangkat</th>
                    <th>aplikasi</th>
                    <th>jaringan</th>
                    <th>lokasi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($rows as $item)
                    @php $klass = strtolower($item['hasil_deteksi']['kategori'] ?? ''); @endphp
                    <tr class="row">
                        <td class="id">#{{ $item['id'] }}</td>
                        <td><div class="msg">{{ $item['pesan'] }}</div></td>
                        <td>
                            <span class="badge {{ $klass }}">{{ $item['hasil_deteksi']['kategori'] }}</span>
                            <div class="mini">keyakinan: {{ number_format($item['hasil_deteksi']['keyakinan'] * 100, 0) }}%</div>
                            <div class="mini">probabilitas: {{ json_encode($item['hasil_deteksi']['probabilitas']) }}</div>
                        </td>
                        <td>{{ $item['waktu_deteksi'] }}</td>
                        <td>{{ $item['created_at'] }}</td>
                        <td>
                            @if($item['perangkat'])
                                <div><strong>{{ $item['perangkat']['manufacturer'] }} {{ $item['perangkat']['model'] }}</strong></div>
                                <div class="mini">{{ $item['perangkat']['installation_id'] }}</div>
                                <div class="mini">Android {{ $item['perangkat']['android_version'] }} · SDK {{ $item['perangkat']['sdk_int'] }}</div>
                            @else
                                <span class="muted">null</span>
                            @endif
                        </td>
                        <td>
                            @if($item['aplikasi'])
                                <div><strong>{{ $item['aplikasi']['app_name'] }}</strong></div>
                                <div class="mini">{{ $item['aplikasi']['package_name'] }}</div>
                                <div class="mini">v{{ $item['aplikasi']['version_name'] }} · {{ $item['aplikasi']['build_type'] }}</div>
                            @else
                                <span class="muted">null</span>
                            @endif
                        </td>
                        <td>
                            <div><strong>{{ $item['jaringan']['connection_type'] ?? 'null' }}</strong></div>
                            <div class="mini">local: {{ $item['jaringan']['local_ip'] ?? 'null' }}</div>
                            <div class="mini">server: {{ $item['jaringan']['server_observed_ip'] ?? 'null' }}</div>
                        </td>
                        <td>
                            @if($item['lokasi']['permission_granted'] === true)
                                <span class="badge normal">true</span>
                                <div class="mini">{{ $item['lokasi']['latitude'] }}, {{ $item['lokasi']['longitude'] }}</div>
                                <div class="mini">± {{ $item['lokasi']['accuracy_m'] }} m</div>
                            @else
                                <span class="muted">{{ $item['lokasi']['permission_granted'] === false ? 'false' : 'null' }}</span>
                            @endif
                        </td>
                        <td><button class="btn" type="button" onclick='showJson(@json($item))'>Detail</button></td>
                    </tr>
                @empty
                    <tr><td colspan="10" style="padding:42px;text-align:center;color:#718096">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="muted">Menampilkan {{ $detections->firstItem() ?? 0 }}–{{ $detections->lastItem() ?? 0 }} dari {{ $detections->total() }}</div>
            <div>{{ $detections->links() }}</div>
        </div>
    </div>
</div>

<div class="modal" id="modal" onclick="if(event.target===this)closeJson()">
    <div class="modal-box">
        <div class="modal-head">
            <div><strong id="modalTitle">Detail JSON</strong><div class="muted" style="font-size:12px">Struktur dibuat mengikuti response API Tangkis.</div></div>
            <button class="btn light" type="button" onclick="closeJson()">Tutup</button>
        </div>
        <div class="modal-body">
            <div class="sections">
                <div class="section full">
                    <h3>Response JSON — data[0]</h3>
                    <pre class="raw" id="rawJson"></pre>
                </div>
                <div class="section">
                    <h3>hasil_deteksi</h3>
                    <div class="kv" id="resultBox"></div>
                </div>
                <div class="section">
                    <h3>waktu</h3>
                    <div class="kv" id="timeBox"></div>
                </div>
                <div class="section">
                    <h3>perangkat</h3>
                    <div class="kv" id="deviceBox"></div>
                </div>
                <div class="section">
                    <h3>aplikasi</h3>
                    <div class="kv" id="appBox"></div>
                </div>
                <div class="section">
                    <h3>jaringan</h3>
                    <div class="kv" id="networkBox"></div>
                </div>
                <div class="section">
                    <h3>lokasi</h3>
                    <div class="kv" id="locationBox"></div>
                </div>
                <div class="section full">
                    <h3>pesan</h3>
                    <div id="messageBox" style="white-space:pre-wrap;line-height:1.6"></div>
                </div>
                <div class="section full">
                    <h3>tanda_bahaya</h3>
                    <div class="danger" id="dangerBox"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const esc=(v)=>{const d=document.createElement('div');d.textContent=v??'null';return d.innerHTML;};
const r=(k,v)=>`<div class="k">${esc(k)}</div><div>${esc(v)}</div>`;

function showJson(d){
    document.getElementById('modalTitle').textContent=`Detail JSON — ID ${d.id}`;
    document.getElementById('rawJson').textContent=JSON.stringify({data:[d]},null,2);

    const h=d.hasil_deteksi||{};
    document.getElementById('resultBox').innerHTML=
        r('kategori',h.kategori)+r('keyakinan',h.keyakinan)+r('probabilitas',JSON.stringify(h.probabilitas||{}));

    document.getElementById('timeBox').innerHTML=
        r('waktu_deteksi',d.waktu_deteksi)+r('created_at',d.created_at);

    const p=d.perangkat||{};
    document.getElementById('deviceBox').innerHTML=
        r('id',p.id)+r('installation_id',p.installation_id)+r('manufacturer',p.manufacturer)+
        r('brand',p.brand)+r('model',p.model)+r('device',p.device)+
        r('android_version',p.android_version)+r('sdk_int',p.sdk_int)+
        r('architecture',p.architecture)+r('is_emulator',p.is_emulator);

    const a=d.aplikasi||{};
    document.getElementById('appBox').innerHTML=
        r('app_name',a.app_name)+r('package_name',a.package_name)+r('version_name',a.version_name)+
        r('version_code',a.version_code)+r('build_type',a.build_type);

    const n=d.jaringan||{};
    document.getElementById('networkBox').innerHTML=
        r('connection_type',n.connection_type)+r('local_ip',n.local_ip)+r('server_observed_ip',n.server_observed_ip);

    const l=d.lokasi||{};
    document.getElementById('locationBox').innerHTML=
        r('permission_granted',l.permission_granted)+r('latitude',l.latitude)+
        r('longitude',l.longitude)+r('accuracy_m',l.accuracy_m);

    document.getElementById('messageBox').textContent=d.pesan||'null';
    const danger=Array.isArray(h.tanda_bahaya)?h.tanda_bahaya:[];
    document.getElementById('dangerBox').innerHTML=danger.length
        ? danger.map(x=>`<span class="badge penipuan">${esc(x)}</span>`).join('')
        : '<span class="muted">[]</span>';

    document.getElementById('modal').classList.add('open');
}
function closeJson(){document.getElementById('modal').classList.remove('open');}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeJson();});
</script>
</body>
</html>
