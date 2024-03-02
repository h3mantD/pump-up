import { Route, Routes } from "react-router-dom";
import "./App.scss";
import Header from "./components/Header/Header";

function App() {
  return (
    <>
      <Header />
      <Routes>
        <Route path="/" element={<h1>landing </h1>} />
        <Route path="/home" element={<h1>Home</h1>} />
        <Route path="/contact-us" element={<h1>contact us</h1>} />
        <Route path="/product/:id" element={<h1>product</h1>} />
      </Routes>
      <h1>footer</h1>
    </>
  );
}

export default App;
