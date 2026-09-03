import http from 'k6/http';
import { check } from 'k6';

export const options = {
  scenarios: {
    ticket_rush: {
      executor: 'ramping-arrival-rate',
      startRate: 0,
      timeUnit: '1s',
      preAllocatedVUs: 400,
      stages: [
        { target: 200, duration: '10s' },
        { target: 500, duration: '2m' }, // Serangan dipertahankan selama 2 menit agar terekam di grafik Hostinger
        { target: 0, duration: '10s' },
      ],
    },
  },
};

export default function () {
  const url = 'https://testt.nanyang.sch.id/api/checkout-simulation';

  // We need to use valid event_session_id and seat_id. 
  // Assuming EventSession ID 1 exists, and Seat IDs 1 to 120 exist.
  const payload = JSON.stringify({
    event_session_id: 1,
    seat_id: 564, // Menggunakan bangku ID 564 yang masih available
  });

  const params = {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
  };

  const response = http.post(url, payload, params);

  // Verifikasi respons
  check(response, {
    'status is 200 (Success)': (r) => r.status === 200,
    'status is 404 (Seat Not Found)': (r) => r.status === 404,
    'status is 409 (Seat taken/conflict)': (r) => r.status === 409,
    'status is 500 (Server Error / Deadlock)': (r) => r.status === 500,
  });
}
