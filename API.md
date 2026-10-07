# Tangkis API v1

Base URL lokal:

`http://127.0.0.1:8000/api/v1`

Base URL staging akan diberikan Railway setelah domain dibuat.

## GET /health

Response:

```json
{
  "status": "ok",
  "service": "tangkis-api",
  "version": "v1",
  "time": "2026-10-07T12:00:00+00:00"
}
```

## POST /detections

```json
{
  "pesan": "Segera kirim OTP untuk klaim hadiah.",
  "kategori": "PENIPUAN",
  "keyakinan": 0.92,
  "probabilitas": {
    "PENIPUAN": 0.92,
    "PROMO": 0.06,
    "NORMAL": 0.02
  },
  "tanda_bahaya": [
    "Minta kode OTP/PIN",
    "Desakan waktu"
  ],
  "waktu": "2026-10-07T11:00:00+00:00"
}
```

## GET /detections

Optional query:

- `limit=1..100`
- `category=NORMAL|PENIPUAN|PROMO`

## GET /stats

Response contains shared totals for all detected messages currently stored in staging.
