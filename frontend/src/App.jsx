import { Route, Routes } from "react-router-dom";
import "./App.scss";
import Header from "@/components/Header/Header.jsx";
import Landing from "@/screens/Landing/Landing.jsx";
import ProductDetail from "@/screens/ProductDetail/ProductDetail.jsx";
import Footer from "@/components/Footer/Footer";

function App() {
  return (
    <>
      <Header />
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/contact-us" element={<h1>contact us</h1>} />
        <Route path="/product/:id" element={<ProductDetail />} />
      </Routes>
      <Footer />
    </>
  );
}

export default App;
