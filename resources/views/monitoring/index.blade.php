<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tangkis Monitoring</title>
    {{-- Auto-refresh ditangani JS (bisa dijeda). Meta refresh hanya cadangan kalau JS mati. --}}
    <noscript><meta http-equiv="refresh" content="30"></noscript>
    <script>try{var t=localStorage.getItem('tangkis.theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>

    <style>
        /* Warna dibuat lembut: bukan putih/hitam murni, supaya tidak capek di mata kalau dibuka lama. */
        :root{
            color-scheme:light;
            --bg:#e5ebee; --surface:#ffffff; --ink:#1b232b; --ink2:#3a4651; --muted:#56636f;
            --line:#b2bfc6; --line2:#d5dee3; --hover:#f2f7f9; --head-bg:#e6eff3; --accent:#1b6a82; --accent-soft:#d9ebf1;
            --top-bg:#14404f; --top-ink:#eaf3f6; --top-line:rgba(255,255,255,.25);
            --c-total:#1f5fa6; --c-total-bg:#e1ecf8; --c-device:#0f766e; --c-device-bg:#d9f0ec; --row-bad:#fdf1ef;
            --b-normal-bg:#e2f2e7; --b-normal:#176234;
            --b-promo-bg:#fbeccd;  --b-promo:#8a4b08;
            --b-penipuan-bg:#fbe1de; --b-penipuan:#a1271c;
            --b-other-bg:#e9e9e6;  --b-other:#4a525c;
            --bar-normal:#2f8f55; --bar-promo:#c27a12; --bar-penipuan:#c0392b; --bar-other:#7b8591;
            --code-bg:#f1f0eb;
        }
        @media (prefers-color-scheme: dark){
            :root:not([data-theme="light"]){
                color-scheme:dark;
                --bg:#0e1316; --surface:#182026; --ink:#e3e8eb; --ink2:#c0c9cf; --muted:#98a6b0;
                --line:#3d4d57; --line2:#2d3a42; --hover:#202b33; --head-bg:#1f2c34; --accent:#7cc7de; --accent-soft:#1d3440;
                --top-bg:#0c2530; --top-ink:#e6f1f5; --top-line:rgba(255,255,255,.22);
                --c-total:#82b6ee; --c-total-bg:#1b2d44; --c-device:#6fd6c9; --c-device-bg:#173a37; --row-bad:#2e1d1d;
                --b-normal-bg:#1f3a2b; --b-normal:#86d9a4;
                --b-promo-bg:#3e3217;  --b-promo:#f0c373;
                --b-penipuan-bg:#432424; --b-penipuan:#ff9f98;
                --b-other-bg:#2b2f36;  --b-other:#b5bcc6;
                --bar-normal:#4fb27a; --bar-promo:#d99a3a; --bar-penipuan:#e0675a; --bar-other:#8a94a1;
                --code-bg:#262a30;
            }
        }
        :root[data-theme="dark"]{
            color-scheme:dark;
            --bg:#0e1316; --surface:#182026; --ink:#e3e8eb; --ink2:#c0c9cf; --muted:#98a6b0;
            --line:#3d4d57; --line2:#2d3a42; --hover:#202b33; --head-bg:#1f2c34; --accent:#7cc7de; --accent-soft:#1d3440;
            --top-bg:#0c2530; --top-ink:#e6f1f5; --top-line:rgba(255,255,255,.22);
            --c-total:#82b6ee; --c-total-bg:#1b2d44; --c-device:#6fd6c9; --c-device-bg:#173a37; --row-bad:#2e1d1d;
            --b-normal-bg:#1f3a2b; --b-normal:#86d9a4;
            --b-promo-bg:#3e3217;  --b-promo:#f0c373;
            --b-penipuan-bg:#432424; --b-penipuan:#ff9f98;
            --b-other-bg:#2b2f36;  --b-other:#b5bcc6;
            --bar-normal:#4fb27a; --bar-promo:#d99a3a; --bar-penipuan:#e0675a; --bar-other:#8a94a1;
            --code-bg:#262a30;
        }

        *{box-sizing:border-box}
        html{-webkit-text-size-adjust:100%}
        body{margin:0;background:var(--bg);color:var(--ink);
            font-family:system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
            font-size:15px;line-height:1.6}
        a{color:var(--accent)}
        button,select,input{font:inherit;color:inherit}
        .muted{color:var(--muted)}
        .nil{color:var(--muted);opacity:.7}
        :focus-visible{outline:2px solid var(--accent);outline-offset:2px}

        .wrap{max-width:1360px;margin:0 auto;padding:24px 28px 48px}

        /* ---------- Header ---------- */
        .topbar{background:var(--top-bg);color:var(--top-ink);border-bottom:1px solid var(--line)}
        .topbar .top p,.topbar .tools{color:color-mix(in srgb,var(--top-ink) 75%,transparent)}
        .topbar .btn{background:rgba(255,255,255,.1);border-color:var(--top-line);color:var(--top-ink)}
        .topbar .btn:hover{background:rgba(255,255,255,.2);border-color:var(--top-ink)}
        .topbar :focus-visible{outline-color:var(--top-ink)}
        .top{max-width:1360px;margin:0 auto;padding:18px 28px;display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}
        .top h1{margin:0;font-size:24px;font-weight:650;letter-spacing:-.01em;line-height:1.25}
        .top p{margin:2px 0 0;color:var(--muted)}
        .tools{display:flex;align-items:center;gap:10px;flex-wrap:wrap;color:var(--muted)}

        /* ---------- Tombol & input ---------- */
        .btn{height:38px;display:inline-flex;align-items:center;justify-content:center;padding:0 14px;border:1px solid var(--line);
            border-radius:6px;background:var(--surface);color:var(--ink);cursor:pointer;text-decoration:none;white-space:nowrap}
        .btn:hover{background:var(--hover);border-color:var(--muted)}
        .btn[disabled]{opacity:.4;cursor:default}
        .btn.sm{height:32px;padding:0 11px;font-size:14px}
        .btn.pri{color:var(--accent);border-color:var(--accent);font-weight:600}
        .btn.pri:hover{background:var(--accent);color:var(--surface);border-color:var(--accent)}
        .ctl{height:38px;border:1px solid var(--line);border-radius:6px;background:var(--surface);padding:0 11px;min-width:0}
        select.ctl{min-width:230px;max-width:100%}
        .search{flex:1;min-width:220px;max-width:360px}
        .search input{width:100%}

        /* ---------- Statistik ---------- */
        .stats{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:14px}
        .stat{--c:var(--ink);--cb:var(--surface);background:var(--cb);border:1px solid color-mix(in srgb,var(--c) 35%,var(--line));border-radius:8px;padding:16px 20px}
        .s-total{--c:var(--c-total);--cb:var(--c-total-bg)}
        .s-normal{--c:var(--b-normal);--cb:var(--b-normal-bg)}
        .s-promo{--c:var(--b-promo);--cb:var(--b-promo-bg)}
        .s-penipuan{--c:var(--b-penipuan);--cb:var(--b-penipuan-bg)}
        .s-device{--c:var(--c-device);--cb:var(--c-device-bg)}
        .stat .label{color:var(--c);font-weight:600}
        .stat .num{font-size:30px;font-weight:650;line-height:1.2;margin-top:2px;color:var(--c);font-variant-numeric:tabular-nums}
        .stat .sub{color:var(--c);font-size:14px}

        /* ---------- Panel data ---------- */
        .panel{margin-top:20px;background:var(--surface);border:1px solid var(--line);border-radius:8px;overflow:hidden}
        .panel-head{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;background:var(--head-bg);border-bottom:1px solid var(--line)}
        .panel-head h2{margin:0;font-size:18px;font-weight:650;color:var(--accent)}
        .panel-head .count{color:var(--muted);font-size:14px}

        .tabs{display:flex;gap:4px;padding:8px 12px 0;border-bottom:1px solid var(--line);overflow-x:auto}
        .tabs a{padding:9px 12px;color:var(--ink2);text-decoration:none;border-bottom:2px solid transparent;margin-bottom:-1px;white-space:nowrap}
        .tabs a span{color:var(--tc,var(--muted));font-weight:600;font-size:14px;margin-left:5px;font-variant-numeric:tabular-nums}
        .tabs a.t-all{--tc:var(--accent)} .tabs a.t-normal{--tc:var(--b-normal)} .tabs a.t-promo{--tc:var(--b-promo)} .tabs a.t-penipuan{--tc:var(--b-penipuan)}
        .tabs a:hover{color:var(--ink)}
        .tabs a.on{color:var(--ink);font-weight:600;border-bottom-color:var(--tc,var(--accent));border-bottom-width:3px}

        .bar{padding:12px 20px;display:flex;gap:12px;align-items:center;flex-wrap:wrap;background:var(--head-bg);border-bottom:1px solid var(--line)}
        .spacer{flex:1}

        .table-scroll{overflow-x:auto}
        table{width:100%;border-collapse:collapse;min-width:980px}
        thead th{padding:10px 16px;text-align:left;font-weight:600;font-size:14px;color:var(--ink2);background:var(--head-bg);border-bottom:1px solid var(--line);white-space:nowrap}
        tbody td{padding:16px;border-bottom:1px solid var(--line2);vertical-align:top}
        tbody tr.row{cursor:pointer}
        tbody tr.row:hover{background:var(--hover)}
        tbody tr.row.bad{background:var(--row-bad)}
        tbody tr.row.bad:hover{background:color-mix(in srgb,var(--b-penipuan-bg) 75%,var(--surface))}
        tbody tr:last-child td{border-bottom:0}
        th+th,td+td{border-left:1px solid var(--line2)}
        tr[hidden]{display:none}
        .id{color:var(--muted);white-space:nowrap;font-variant-numeric:tabular-nums}
        .msg{max-width:440px;white-space:pre-wrap;word-break:break-word;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
        .sub{color:var(--muted);font-size:14px}
        .nowrap{white-space:nowrap}

        .badge{display:inline-block;padding:2px 9px;border-radius:4px;font-size:13.5px;font-weight:600;letter-spacing:.01em;line-height:1.5}
        .badge.normal{background:var(--b-normal-bg);color:var(--b-normal)}
        .badge.promo{background:var(--b-promo-bg);color:var(--b-promo)}
        .badge.penipuan{background:var(--b-penipuan-bg);color:var(--b-penipuan)}
        .badge.other{background:var(--b-other-bg);color:var(--b-other)}
        .warn{color:var(--b-penipuan);font-size:14px}

        .empty{padding:56px 20px;text-align:center;color:var(--muted)}
        .empty b{display:block;color:var(--ink);font-size:16px;margin-bottom:4px}

        /* ---------- Pager ---------- */
        .pager{padding:12px 20px;display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap;background:var(--head-bg);border-top:1px solid var(--line)}
        .pager-nav{display:flex;gap:4px;align-items:center;flex-wrap:wrap}
        .pager-nav a,.pager-nav span{min-width:36px;height:36px;padding:0 10px;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;text-decoration:none;color:var(--ink)}
        .pager-nav a:hover{background:var(--hover);outline:1px solid var(--line)}
        .pager-nav .cur{background:var(--accent);color:var(--surface);font-weight:600}
        .pager-nav .off{color:var(--muted);opacity:.5}
        .pager-nav .gap{color:var(--muted);min-width:20px;padding:0}

        /* ---------- Panel detail ---------- */
        .scrim{position:fixed;inset:0;background:rgba(0,0,0,.45);opacity:0;pointer-events:none;transition:opacity .15s;z-index:40}
        .scrim.open{opacity:1;pointer-events:auto}
        .drawer{position:fixed;top:0;right:0;bottom:0;width:min(600px,100%);background:var(--surface);border-left:1px solid var(--line);z-index:41;
            display:flex;flex-direction:column;transform:translateX(100%);transition:transform .2s ease-out}
        .drawer.open{transform:none}
        .drawer-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 20px;background:var(--head-bg);border-bottom:1px solid var(--line)}
        .drawer-head h3{margin:0;font-size:18px;font-weight:650}
        .drawer-head p{margin:0;font-size:14px;color:var(--muted)}
        .drawer-actions{display:flex;gap:6px}
        .drawer-body{padding:16px 20px 32px;overflow-y:auto;flex:1;background:var(--bg)}
        .blk{background:var(--surface);border:1px solid var(--line);border-radius:8px;overflow:hidden;padding-bottom:14px}
        .blk+.blk{margin-top:12px}
        .blk h4{margin:0;padding:9px 14px;font-size:15px;font-weight:650;color:var(--accent);background:var(--head-bg);border-bottom:1px solid var(--line)}
        .blk.t-blue h4{background:var(--c-total-bg);color:var(--c-total)}
        .blk.t-teal h4{background:var(--c-device-bg);color:var(--c-device)}
        .blk.t-amber h4{background:var(--b-promo-bg);color:var(--b-promo)}
        .blk.t-green h4,.blk.cat-normal h4{background:var(--b-normal-bg);color:var(--b-normal)}
        .blk.t-red h4,.blk.cat-penipuan h4{background:var(--b-penipuan-bg);color:var(--b-penipuan)}
        .blk.cat-promo h4{background:var(--b-promo-bg);color:var(--b-promo)}
        .blk.cat-other h4{background:var(--b-other-bg);color:var(--b-other)}
        .blk>:not(h4){margin-left:14px;margin-right:14px}
        .blk>h4+*{margin-top:14px}
        .blk>details{margin:14px 14px 0}
        .verdict{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
        .verdict .big{font-size:28px;font-weight:600;line-height:1.1;text-align:right;font-variant-numeric:tabular-nums}
        .verdict .big small{display:block;font-size:14px;font-weight:400;color:var(--muted);margin-top:3px}
        .prob{display:grid;grid-template-columns:96px 1fr 48px;gap:12px;align-items:center;margin-top:10px}
        .prob .track{height:8px;border-radius:4px;background:var(--line2);overflow:hidden}
        .prob .track i{display:block;height:100%}
        .prob .pv{text-align:right;font-variant-numeric:tabular-nums;color:var(--ink2)}
        .i-normal{background:var(--bar-normal)} .i-promo{background:var(--bar-promo)} .i-penipuan{background:var(--bar-penipuan)} .i-other{background:var(--bar-other)}
        .msgbox{white-space:pre-wrap;word-break:break-word;line-height:1.7;font-size:16px}
        .chips{display:flex;gap:6px;flex-wrap:wrap}
        dl{margin:0;display:grid;grid-template-columns:150px minmax(0,1fr);gap:7px 14px}
        dt{color:var(--muted)}
        dd{margin:0;word-break:break-word}
        .maplink{display:inline-block;margin-top:12px!important}
        details summary{cursor:pointer;color:var(--ink2)}
        .raw{margin:12px 0 0;background:var(--code-bg);color:var(--ink);border-radius:6px;padding:14px;overflow:auto;
            font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:13.5px;line-height:1.6;max-height:340px}

        .toast{position:fixed;left:50%;bottom:24px;transform:translateX(-50%);background:var(--ink);color:var(--surface);padding:9px 16px;border-radius:6px;opacity:0;pointer-events:none;transition:opacity .2s;z-index:60}
        .toast.show{opacity:1}

        @media(max-width:1100px){.stats{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:700px){
            body{font-size:16px}
            .wrap{padding:18px 14px 36px}
            .stats{grid-template-columns:repeat(2,minmax(0,1fr))}
            .stat{padding:14px 16px}.stat .num{font-size:26px}
            .bar{padding:12px 14px}.search{max-width:none;flex-basis:100%}
            select.ctl{width:100%}
            .panel-head,.pager{padding-left:14px;padding-right:14px}
            dl{grid-template-columns:1fr;gap:0}dt{margin-top:10px}
        }
        @media(prefers-reduced-motion:reduce){*{transition:none!important}}
    </style>
</head>
<body>

@php
    $rows = $detections->items();

    $total = (int) ($stats['total'] ?? 0);
    $pct = fn ($n) => $total > 0 ? round(($n / $total) * 100, 1) : 0;

    $hasFilter = $kategori !== '' || $deviceId !== '';
    $knownCats = ['normal', 'promo', 'penipuan'];
@endphp

<header class="topbar">
    <div class="top">
        <div>
            <h1>Tangkis Monitoring</h1>
            <p>Hasil deteksi pesan dari perangkat. Data per <span id="serverTime" data-iso="{{ $meta['server_time'] }}">{{ $meta['server_time'] }}</span>.</p>
        </div>
        <div class="tools">
            <span id="liveText">Refresh otomatis…</span>
            <button class="btn sm" type="button" id="pauseBtn">Jeda</button>
            <button class="btn sm" type="button" onclick="location.reload()">Muat ulang</button>
            <button class="btn sm" type="button" id="themeBtn">Mode gelap</button>
        </div>
    </div>
</header>

<main class="wrap">

    {{-- Ringkasan --}}
    <section class="stats" aria-label="Ringkasan">
        <div class="stat s-total"><div class="label">Total deteksi</div><div class="num">{{ number_format($stats['total']) }}</div><div class="sub">semua kategori</div></div>
        <div class="stat s-normal"><div class="label">Normal</div><div class="num">{{ number_format($stats['normal']) }}</div><div class="sub">{{ $pct($stats['normal']) }}% dari total</div></div>
        <div class="stat s-promo"><div class="label">Promo</div><div class="num">{{ number_format($stats['promo']) }}</div><div class="sub">{{ $pct($stats['promo']) }}% dari total</div></div>
        <div class="stat s-penipuan"><div class="label">Penipuan</div><div class="num">{{ number_format($stats['penipuan']) }}</div><div class="sub">{{ $pct($stats['penipuan']) }}% dari total</div></div>
        <div class="stat s-device"><div class="label">Perangkat</div><div class="num">{{ number_format($stats['devices']) }}</div><div class="sub">terdaftar</div></div>
    </section>

    {{-- Data --}}
    <section class="panel">
        <div class="panel-head">
            <h2>Data deteksi</h2>
            <div class="count">
                {{ number_format($meta['count']) }} hasil{{ $hasFilter ? ' (terfilter)' : '' }}
                · halaman {{ $meta['current_page'] }} dari {{ $meta['total_pages'] }}
            </div>
        </div>

        <nav class="tabs" aria-label="Filter kategori">
            <a href="{{ request()->fullUrlWithQuery(['kategori' => null, 'page' => null]) }}" class="t-all {{ $kategori === '' ? 'on' : '' }}">Semua<span>{{ number_format($stats['total']) }}</span></a>
            @foreach($categories as $item)
                <a href="{{ request()->fullUrlWithQuery(['kategori' => $item, 'page' => null]) }}" class="t-{{ strtolower($item) }} {{ $kategori === $item ? 'on' : '' }}">{{ ucfirst(strtolower($item)) }}<span>{{ number_format($stats[strtolower($item)] ?? 0) }}</span></a>
            @endforeach
        </nav>

        <div class="bar">
            <form method="GET" action="{{ route('monitoring.index') }}">
                @if($kategori !== '')
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                @endif
                <select class="ctl" name="device_id" aria-label="Filter perangkat" onchange="this.form.submit()">
                    <option value="">Semua perangkat</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" @selected($deviceId === (string) $device->id)>
                            {{ $device->manufacturer }} {{ $device->model }}
                        </option>
                    @endforeach
                </select>
                <noscript><button class="btn" type="submit">Terapkan</button></noscript>
            </form>

            @if($hasFilter)
                <a class="btn" href="{{ route('monitoring.index') }}">Reset filter</a>
            @endif

            <div class="spacer"></div>

            <div class="search">
                <input class="ctl" type="search" id="quickSearch" placeholder="Cari pesan di halaman ini" autocomplete="off" aria-label="Cari pesan di halaman ini">
            </div>
        </div>

        <div class="table-scroll">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Pesan</th>
                    <th>Hasil</th>
                    <th>Waktu</th>
                    <th>Perangkat</th>
                    <th>Jaringan</th>
                    <th>Lokasi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="tbody">
                @forelse($rows as $i => $item)
                    @php
                        $h = $item['hasil_deteksi'] ?? [];
                        $kat = (string) ($h['kategori'] ?? '');
                        $klass = in_array(strtolower($kat), $knownCats, true) ? strtolower($kat) : 'other';
                        $conf = (float) ($h['keyakinan'] ?? 0);
                        $flags = is_array($h['tanda_bahaya'] ?? null) ? $h['tanda_bahaya'] : [];
                        $dev = $item['perangkat'] ?? null;
                        $app = $item['aplikasi'] ?? null;
                        $net = $item['jaringan'] ?? [];
                        $loc = $item['lokasi'] ?? [];
                        $perm = $loc['permission_granted'] ?? null;
                    @endphp
                    <tr class="row {{ $klass === 'penipuan' ? 'bad' : '' }}" data-index="{{ $i }}" data-search="{{ mb_strtolower((string) ($item['pesan'] ?? '')) }}" tabindex="0">
                        <td class="id">#{{ $item['id'] }}</td>
                        <td><div class="msg">{{ $item['pesan'] }}</div></td>
                        <td>
                            <span class="badge {{ $klass }}">{{ $kat !== '' ? ucfirst(strtolower($kat)) : 'null' }}</span>
                            <div class="sub">keyakinan {{ number_format($conf * 100, 0) }}%</div>
                            @if(count($flags))
                                <div class="warn">{{ count($flags) }} tanda bahaya</div>
                            @endif
                        </td>
                        <td class="nowrap"><span data-time="{{ $item['waktu_deteksi'] }}">{{ $item['waktu_deteksi'] }}</span></td>
                        <td>
                            @if($dev)
                                <div>{{ $dev['manufacturer'] }} {{ $dev['model'] }}</div>
                                <div class="sub">Android {{ $dev['android_version'] }}@if($app) · {{ $app['app_name'] }} v{{ $app['version_name'] }}@endif</div>
                            @else
                                <span class="nil">—</span>
                            @endif
                        </td>
                        <td>
                            @if(!empty($net['connection_type']))
                                <div>{{ $net['connection_type'] }}</div>
                                <div class="sub">{{ $net['server_observed_ip'] ?? '' }}</div>
                            @else
                                <span class="nil">—</span>
                            @endif
                        </td>
                        <td>
                            @if($perm === true)
                                <div>Diizinkan</div>
                                <div class="sub">± {{ $loc['accuracy_m'] }} m</div>
                            @elseif($perm === false)
                                <div class="muted">Ditolak</div>
                            @else
                                <span class="nil">—</span>
                            @endif
                        </td>
                        <td><button class="btn sm pri" type="button" data-open="{{ $i }}" aria-label="Lihat detail #{{ $item['id'] }}">Detail</button></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty">
                                <b>Belum ada data</b>
                                @if($hasFilter)
                                    Tidak ada deteksi yang cocok dengan filter ini. <a href="{{ route('monitoring.index') }}">Reset filter</a>
                                @else
                                    Data akan muncul setelah perangkat mengirim hasil deteksi.
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
                <tr id="noMatch" hidden>
                    <td colspan="8"><div class="empty"><b>Tidak ada pesan yang cocok</b>Pencarian ini hanya mencakup halaman yang sedang tampil.</div></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="pager">
            <div class="muted">Menampilkan {{ $detections->firstItem() ?? 0 }}–{{ $detections->lastItem() ?? 0 }} dari {{ number_format($detections->total()) }}</div>

            @if($detections->lastPage() > 1)
                @php
                    $cur = $detections->currentPage();
                    $last = $detections->lastPage();
                    $pages = collect([1, $last])->merge(range(max(1, $cur - 2), min($last, $cur + 2)))->unique()->sort()->values();
                    $prev = null;
                @endphp
                <nav class="pager-nav" aria-label="Halaman">
                    @if($detections->onFirstPage())
                        <span class="off">Sebelumnya</span>
                    @else
                        <a href="{{ $detections->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                    @endif

                    @foreach($pages as $p)
                        @if($prev !== null && $p - $prev > 1)
                            <span class="gap">…</span>
                        @endif
                        @if($p === $cur)
                            <span class="cur" aria-current="page">{{ $p }}</span>
                        @else
                            <a href="{{ $detections->url($p) }}">{{ $p }}</a>
                        @endif
                        @php $prev = $p; @endphp
                    @endforeach

                    @if($detections->hasMorePages())
                        <a href="{{ $detections->nextPageUrl() }}" rel="next">Berikutnya</a>
                    @else
                        <span class="off">Berikutnya</span>
                    @endif
                </nav>
            @endif
        </div>
    </section>
</main>

{{-- Panel detail --}}
<div class="scrim" id="scrim"></div>
<aside class="drawer" id="drawer" role="dialog" aria-modal="true" aria-labelledby="dTitle" aria-hidden="true">
    <div class="drawer-head">
        <div>
            <h3 id="dTitle">Detail deteksi</h3>
            <p>Tombol panah kiri/kanan untuk pindah data, Esc untuk menutup.</p>
        </div>
        <div class="drawer-actions">
            <button class="btn sm" type="button" id="dPrev" aria-label="Data sebelumnya">‹</button>
            <button class="btn sm" type="button" id="dNext" aria-label="Data berikutnya">›</button>
            <button class="btn sm" type="button" id="dClose">Tutup</button>
        </div>
    </div>
    <div class="drawer-body" id="dBody"></div>
</aside>

<div class="toast" id="toast" role="status"></div>

<script>
/* Data baris halaman ini dikirim lewat JSON di <script> (bukan atribut onclick),
   supaya pesan yang mengandung tanda ' atau " tidak merusak HTML. */
const DATA = @json($rows);

const $  = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
const esc = v => { const d = document.createElement('div'); d.textContent = (v === null || v === undefined) ? '' : String(v); return d.innerHTML; };
const empty = v => v === null || v === undefined || v === '';
const val = v => empty(v) ? '<span class="nil">—</span>' : esc(v);
const kv  = (k, v) => `<dt>${esc(k)}</dt><dd>${val(v)}</dd>`;
const known = ['normal', 'promo', 'penipuan'];
const kls = k => known.includes(String(k).toLowerCase()) ? String(k).toLowerCase() : 'other';
const cap = s => { s = String(s ?? ''); return s ? s.charAt(0).toUpperCase() + s.slice(1).toLowerCase() : 'null'; };
const pct = v => Number.isFinite(+v) ? Math.round(+v * 100) + '%' : '—';

/* ---------- Format waktu ---------- */
$$('[data-time]').forEach(el => {
    const raw = el.dataset.time;
    if (!/\d{4}-\d{2}-\d{2}/.test(raw)) return;
    const t = Date.parse(raw);
    if (!isNaN(t)) el.textContent = new Date(t).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
});
(() => {
    const st = $('#serverTime'), t = Date.parse(st.dataset.iso);
    if (!isNaN(t)) st.textContent = new Date(t).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
})();

/* ---------- Tema terang / gelap ---------- */
const root = document.documentElement;
const isDark = () => root.dataset.theme ? root.dataset.theme === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches;
const paintTheme = () => { $('#themeBtn').textContent = isDark() ? 'Mode terang' : 'Mode gelap'; };
$('#themeBtn').addEventListener('click', () => {
    const next = isDark() ? 'light' : 'dark';
    root.dataset.theme = next;
    try { localStorage.setItem('tangkis.theme', next); } catch (e) {}
    paintTheme();
});
paintTheme();

/* ---------- Toast ---------- */
let toastT;
function toast(msg) {
    const t = $('#toast'); t.textContent = msg; t.classList.add('show');
    clearTimeout(toastT); toastT = setTimeout(() => t.classList.remove('show'), 1800);
}

/* ---------- Panel detail ---------- */
let cur = -1, lastFocus = null;

function render(d) {
    const h = d.hasil_deteksi || {}, p = d.perangkat || {}, a = d.aplikasi || {}, n = d.jaringan || {}, l = d.lokasi || {};
    const probs = (h.probabilitas && typeof h.probabilitas === 'object') ? Object.entries(h.probabilitas) : [];
    const flags = Array.isArray(h.tanda_bahaya) ? h.tanda_bahaya : [];

    const probHtml = probs.map(([name, v]) => `
        <div class="prob"><span>${esc(cap(name))}</span>
            <div class="track"><i class="i-${kls(name)}" style="width:${Math.max(0, Math.min(100, (+v || 0) * 100))}%"></i></div>
            <span class="pv">${pct(v)}</span></div>`).join('');

    const hasLoc = l.permission_granted === true && !empty(l.latitude) && !empty(l.longitude);
    const map = hasLoc
        ? `<a class="maplink" target="_blank" rel="noopener" href="https://www.google.com/maps?q=${encodeURIComponent(l.latitude)},${encodeURIComponent(l.longitude)}">Buka di Google Maps</a>`
        : '';

    return `
    <div class="blk cat-${kls(h.kategori)}">
        <h4>Hasil deteksi</h4>
        <div class="verdict">
            <span class="badge ${kls(h.kategori)}" style="font-size:16px;padding:3px 12px">${esc(cap(h.kategori))}</span>
            <div class="big">${pct(h.keyakinan)}<small>tingkat keyakinan</small></div>
        </div>
        ${probHtml}
    </div>

    <div class="blk"><h4>Pesan</h4><div class="msgbox">${empty(d.pesan) ? '<span class="nil">—</span>' : esc(d.pesan)}</div></div>

    <div class="blk${flags.length ? ' t-red' : ''}"><h4>Tanda bahaya</h4>
        <div class="chips">${flags.length ? flags.map(x => `<span class="badge penipuan">${esc(x)}</span>`).join('') : '<span class="muted">Tidak ada.</span>'}</div>
    </div>

    <div class="blk"><h4>Waktu</h4><dl>${kv('waktu_deteksi', d.waktu_deteksi)}${kv('created_at', d.created_at)}</dl></div>

    <div class="blk t-blue"><h4>Perangkat</h4><dl>
        ${kv('id', p.id)}${kv('installation_id', p.installation_id)}${kv('manufacturer', p.manufacturer)}${kv('brand', p.brand)}${kv('model', p.model)}
        ${kv('device', p.device)}${kv('android_version', p.android_version)}${kv('sdk_int', p.sdk_int)}${kv('architecture', p.architecture)}${kv('is_emulator', p.is_emulator)}
    </dl></div>

    <div class="blk t-teal"><h4>Aplikasi</h4><dl>
        ${kv('app_name', a.app_name)}${kv('package_name', a.package_name)}${kv('version_name', a.version_name)}${kv('version_code', a.version_code)}${kv('build_type', a.build_type)}
    </dl></div>

    <div class="blk t-amber"><h4>Jaringan</h4><dl>
        ${kv('connection_type', n.connection_type)}${kv('local_ip', n.local_ip)}${kv('server_observed_ip', n.server_observed_ip)}
    </dl></div>

    <div class="blk t-green"><h4>Lokasi</h4><dl>
        ${kv('permission_granted', l.permission_granted)}${kv('latitude', l.latitude)}${kv('longitude', l.longitude)}${kv('accuracy_m', l.accuracy_m)}
    </dl>${map}</div>

    <div class="blk">
        <details>
            <summary>Response JSON (data[0])</summary>
            <pre class="raw" id="rawJson"></pre>
            <button class="btn sm" type="button" id="copyJson" style="margin-top:10px">Salin JSON</button>
        </details>
    </div>`;
}

function openDetail(i) {
    if (i < 0 || i >= DATA.length) return;
    const wasOpen = $('#drawer').classList.contains('open');
    cur = i;
    const d = DATA[i];
    $('#dTitle').textContent = `Detail deteksi #${d.id}`;
    $('#dBody').innerHTML = render(d);
    $('#dBody').scrollTop = 0;
    const json = JSON.stringify({ data: [d] }, null, 2);
    $('#rawJson').textContent = json;
    $('#copyJson').addEventListener('click', () => {
        (navigator.clipboard ? navigator.clipboard.writeText(json) : Promise.reject())
            .then(() => toast('JSON disalin'), () => toast('Gagal menyalin'));
    });
    $('#dPrev').disabled = i === 0;
    $('#dNext').disabled = i === DATA.length - 1;
    if (!wasOpen) {
        lastFocus = document.activeElement;
        $('#drawer').classList.add('open'); $('#scrim').classList.add('open');
        $('#drawer').setAttribute('aria-hidden', 'false');
        $('#dClose').focus();
    }
}
function closeDetail() {
    $('#drawer').classList.remove('open'); $('#scrim').classList.remove('open');
    $('#drawer').setAttribute('aria-hidden', 'true');
    cur = -1;
    if (lastFocus && lastFocus.focus) lastFocus.focus();
}
const drawerOpen = () => $('#drawer').classList.contains('open');

$('#tbody').addEventListener('click', e => {
    const row = e.target.closest('tr.row');
    if (row && !e.target.closest('a')) openDetail(+row.dataset.index);
});
$('#tbody').addEventListener('keydown', e => {
    const row = e.target.closest('tr.row');
    if (row && e.target === row && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); openDetail(+row.dataset.index); }
});
$('#scrim').addEventListener('click', closeDetail);
$('#dClose').addEventListener('click', closeDetail);
$('#dPrev').addEventListener('click', () => openDetail(cur - 1));
$('#dNext').addEventListener('click', () => openDetail(cur + 1));
document.addEventListener('keydown', e => {
    if (!drawerOpen()) return;
    if (e.key === 'Escape') closeDetail();
    else if (e.key === 'ArrowLeft') openDetail(cur - 1);
    else if (e.key === 'ArrowRight') openDetail(cur + 1);
});

/* ---------- Cari pesan (halaman ini saja) ---------- */
const qs = $('#quickSearch');
qs.addEventListener('input', () => {
    const q = qs.value.trim().toLowerCase();
    let shown = 0;
    $$('#tbody tr.row').forEach(tr => {
        const ok = !q || tr.dataset.search.includes(q);
        tr.hidden = !ok; if (ok) shown++;
    });
    $('#noMatch').hidden = !(q && shown === 0 && DATA.length > 0);
});

/* ---------- Auto-refresh 30 detik (berhenti sementara saat detail terbuka / sedang mencari) ---------- */
const REFRESH = 30;
let left = REFRESH, manualPause = false;
try { manualPause = localStorage.getItem('tangkis.paused') === '1'; } catch (e) {}
const busy = () => drawerOpen() || qs.value.trim() !== '';

function paintLive() {
    $('#liveText').textContent = manualPause ? 'Auto-refresh dijeda'
        : busy() ? 'Refresh ditahan sementara'
        : `Refresh dalam ${left} dtk`;
    $('#pauseBtn').textContent = manualPause ? 'Lanjutkan' : 'Jeda';
}
$('#pauseBtn').addEventListener('click', () => {
    manualPause = !manualPause;
    try { localStorage.setItem('tangkis.paused', manualPause ? '1' : '0'); } catch (e) {}
    if (!manualPause) left = REFRESH;
    paintLive();
});
setInterval(() => {
    if (!manualPause && !busy() && !document.hidden) { left--; if (left <= 0) { location.reload(); return; } }
    paintLive();
}, 1000);
paintLive();
</script>
</body>
</html>