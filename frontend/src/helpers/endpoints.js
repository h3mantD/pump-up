const endpoints = {
  products: "/api/v1/products",
  product: (id) => `/api/v1/products/${id}`,
  reviews: (id) => `/api/v1/products/${id}/reviews`,
  tts: "/api/v1/eleven-labs/text-to-speech",
  groq: "/api/v1/groq/chat"
};

export default endpoints;
