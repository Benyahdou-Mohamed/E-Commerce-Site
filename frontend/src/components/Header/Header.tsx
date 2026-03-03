import React, { useState } from "react";
import { Link, useNavigate, useNavigation } from "react-router-dom";
import { CartOverlay } from "../CartOverlay/CartOverlay";
import { useCart } from "../../context/CartContext";

interface Category {
  id: number;
  name: string;
}

type Props = {
  categories: Category[];
  currentCategory: string;
  onCategoryChange: (category: string) => void;
};

export const Header = ({
  categories,
  currentCategory,
  onCategoryChange,
}: Props) => {
  const navigate = useNavigate();
  const handleNav = (category: string) => {
    onCategoryChange(category);
    navigate("/");
  };
  const { isCartOpen, setIsCartOpen } = useCart();
  const { totalCount } = useCart();

  return (
    <nav className="flex justify-between px-20  ">
      {/* LEFT - categories */}
      <div className="flex flex-1">
        {categories?.map((category) => (
          <Link
            key={category.id}
            to={`/${category.name}`}
            data-testid={
              currentCategory === category.name
                ? "active-category-link"
                : "category-link"
            }
            className={`px-3.5 py-5 capitalize font-medium border-b-2 transition-colors ${
              currentCategory === category.name
                ? "border-green-500 text-green-500"
                : "border-transparent text-gray-700 hover:text-green-500"
            }`}
            onClick={() => handleNav(category.name)}
          >
            {category.name}
          </Link>
        ))}
      </div>
      {/* Center - logo */}
      <div className="flex flex-1 justify-center">
        <Link to="/" className="py-2">
          <img src="/a-logo.png" alt="E-commerce" />
        </Link>
      </div>
      {/* Right - Cart */}
      <div className="flex flex-1  justify-end">
        <div className="flex flex-1 justify-end relative">
          <button
            data-testid="cart-btn"
            onClick={() => {
              setIsCartOpen(!isCartOpen);
            }}
            className="relative py-5"
          >
            <img src="/empty-cart.png" alt="cart" className="h-6" />
            {/* Item count bubble */}
            {totalCount > 0 && (
              <span
                className={`absolute top-4.5 -right-2  bg-black text-white text-xs rounded-full w-5 h-5 flex items-center justify-center`}
              >
                {totalCount}
              </span>
            )}
          </button>
          <CartOverlay />
        </div>
      </div>
    </nav>
  );
};
