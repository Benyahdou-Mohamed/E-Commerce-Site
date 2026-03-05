import { useState } from "react";
import "./App.css";
import { Routes, Route } from "react-router-dom";
import { Header } from "./components/Header/Header";
import { ProductPage } from "./pages/ProductPage/ProductPage";
import { useQuery } from "@apollo/client/react";
import { GET_CATEGORIES } from "./graphql/queries";
import type { CategoriesData } from "./types";
import { CartProvider } from "./context/CartContext";
import CategoryPage from "./pages/CategoryPage/CategoryPage";

function App() {
  const [count, setCount] = useState(0);
  //const { data, loading, error } = useQuery<ProductsData>(GET_PRODUCTS);

  const [currentCategory, setCurrentCategory] = useState<string>("all");
  const { data } = useQuery<CategoriesData>(GET_CATEGORIES);
  const categories = data?.categories ?? [];

  return (
    <div className="max-w-8xl mx-auto px-20">
      <CartProvider>
        <Header
          categories={categories}
          currentCategory={currentCategory}
          onCategoryChange={setCurrentCategory}
        />
        <Routes>
          <Route
            path="/"
            element={<CategoryPage category={currentCategory} />}
          />
          <Route
            path="/:category"
            element={<CategoryPage category={currentCategory} />}
          />
          <Route path="/" element={<CategoryPage category="all" />} />

          <Route path="/product/:id" element={<ProductPage />} />
        </Routes>
      </CartProvider>
    </div>
  );
}

export default App;
