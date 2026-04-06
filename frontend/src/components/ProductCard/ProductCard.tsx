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
        selectedAttributes = {
          ...selectedAttributes,
          [att.id]: att.items[0].value,
        };
      });
    }

    addToCart(product, selectedAttributes);
    setIsCartOpen(true);
  };

  return (
    <div
      data-testid={`product-${toKebabCase(product.name)}`}
      onClick={handleClick}
      className="
        group
        relative bg-transparent
        rounded overflow-hidden
        hover:shadow-lg cursor-pointer
        p-4 pb-6 mb-4
        flex flex-col
      "
    >
      {/*  Square image box using aspect-square */}
      <div className="relative w-full aspect-square">
        <img
          className="
            absolute inset-0
            w-full h-full
            object-fill rounded
          "
          src={product.gallery[0]}
          alt={product.name}
        />

        {/* Out of stock overlay */}
        {!product.inStock && (
          <div
            className="
            absolute inset-0
            bg-white/50
            flex items-center justify-center
            text-[#161617] font-thin
            text-lg sm:text-xl
          "
          >
            OUT OF STOCK
          </div>
        )}

        {/* Quick add button — shows on hover */}
        {product.inStock && (
          <button
            onClick={(e) => handleQuickShop(e, product)}
            className="
              absolute z-10 bottom-4 right-4
              w-14 h-14 sm:w-16 sm:h-16
              rounded-full
              flex items-center justify-center
              opacity-0 group-hover:opacity-100
              transition-opacity duration-200
             
            "
          >
            <img
              src="/circleIcon.png"
              className="w-full sm:w-full"
              alt="Add To Cart"
            />
          </button>
        )}
      </div>

      {/* Product info */}
      <div className="mt-4 flex flex-col gap-1">
        <p className="font-thin text-base sm:text-lg">{product.name}</p>
        <p className="font-bold text-base sm:text-lg">
          {price?.currency?.symbol}
          {price.amount.toFixed(2)}
        </p>
      </div>
    </div>
  );
};
