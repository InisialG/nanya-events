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
        { target: 100, duration: '2s' },
        { target: 500, duration: '5s' },
        { target: 500, duration: '10s' },
        { target: 0, duration: '5s' },
      ],
    },
  },
};

export default function () {
  const url = 'https://events.nanyang.sch.id/api/checkout-simulation';

  // We need to use valid event_session_id and seat_id. 
  // Assuming EventSession ID 1 exists, and Seat IDs 1 to 120 exist.
  const payload = JSON.stringify({
    event_session_id: 1,
    seat_id: 559, // Menggunakan bangku ID 559 yang berstatus 'available' di server
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
