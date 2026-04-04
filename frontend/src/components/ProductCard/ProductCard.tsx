import React from "react";
import { useNavigate } from "react-router-dom";
import type { Product } from "../../types";
import { useCart } from "../../context/CartContext";

type Props = {
  product: Product;
};

const toKebabCase = (str: string): string =>
  str.toLowerCase().replace(/\s+/g, "-");

export const ProductCard = ({ product }: Props) => {
  const price = product.prices[0];
  const navigate = useNavigate();
  const { addToCart, setIsCartOpen } = useCart();

  const handleClick = () => {
    navigate(`/product/${product.id}`);
  };

  const handleQuickShop = (e: React.MouseEvent, product: Product): void => {
    e.stopPropagation();
    let selectedAttributes: Record<string, string> = {};

    if (product.attributes[0]?.type === "text") {
      product.attributes.forEach((att) => {
        const id = att.id;
        const item = att.items[0].value;
        selectedAttributes = { ...selectedAttributes, [id]: item };
      });
    }

    addToCart(product, selectedAttributes);
    setIsCartOpen(true);
  };

  return (
    <div
      data-testid={`product-${toKebabCase(product.name)}`}
      onClick={handleClick}
      className="relative bg-transparent rounded overflow-hidden hover:shadow-lg hover:cursor-pointer p-4 pb-6 mb-8 flex flex-col"
    >
      <div className="w-full relative">
        <img
          className="w-full h-[250px] sm:h-[300px] md:h-[350px] lg:h-[400px] object-cover rounded"
          src={product.gallery[0]}
          alt={product.name}
        />
        {!product.inStock && (
          <div className="absolute inset-0 bg-white/50 flex items-center justify-center text-[#161617] font-thin text-lg sm:text-xl md:text-2xl">
            OUT OF STOCK
          </div>
        )}
        {product.inStock && (
          <button
            onClick={(e) => handleQuickShop(e, product)}
            className="absolute z-10 bottom-4 right-4 w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
          >
            <img
              src="circleIcon.png"
              className="w-6 sm:w-8"
              alt="Add To Cart"
            />
          </button>
        )}
      </div>
      <div className="mt-4 flex flex-col">
        <div className="font-thin text-base sm:text-lg md:text-[18px]">
          {product.name}
        </div>
        <div className="font-bold text-base sm:text-lg md:text-[18px] pt-1">
          {price?.currency?.symbol}
          {price.amount.toFixed(2)}
        </div>
      </div>
    </div>
  );
};
