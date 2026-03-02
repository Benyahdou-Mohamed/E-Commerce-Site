import React from "react";
import { Link } from "react-router-dom";
import { GET_PRODUCTS } from "../../graphql/queries";
import type { Product } from "../../types";
import { useNavigate } from "react-router-dom";
import { useCart } from "../../context/CartContext";
type Props = {
  product: Product;
};
const toKebabCase = (str: string): string =>
  str.toLowerCase().replace(/\s+/g, "-");

export const ProductCard = ({ product }: Props) => {
  const price = product.prices[0];
  const navigate = useNavigate();
  const handleClick = () => {
    navigate(`/product/${product.id}`);
  };
  const { addToCart, setIsCartOpen } = useCart();

  const handleQuickShop = (e: React.MouseEvent, product: Product): void => {
    var selectedAttributes: Record<string, string> = {};
    e.stopPropagation();
    if (product.attributes[0]?.type === "text") {
      product.attributes.map((att) => {
        //selectedAttributes[att.id] = att.items[0].value;
        const id = att.id;
        const item = att.items[0].value;
        selectedAttributes = { ...selectedAttributes, [id]: item };
      });
    }
    addToCart(product, selectedAttributes);
    setIsCartOpen(true);
  };
  return (
    <>
      {/*  */}
      <div
        data-testid={`product-${toKebabCase(product.name)}`}
        onClick={handleClick}
        className="relative poin max-w-sm group bg-transparent rounded overflow-hidden hover:shadow-lg hover:cursor-pointer  p-4 pb-6 mb-28"
      >
        <div className="w-full ">
          <img
            className="w-full min-h-[350px] max-h-[350px]"
            src={product.gallery[0]}
            alt="Sunset in the mountains"
          />

          {/* Black overlay */}
          {!product.inStock && (
            <div className="text-[24px] font-thin text-[#161617] absolute w-full h-full inset-0 bg-white/50 text-center align-middle content-center self-center">
              {" "}
              OUT OF STOCK
            </div>
          )}
        </div>
        {product.inStock && (
          <button
            onClick={(e) => {
              handleQuickShop(e, product);
            }}
            className="w-16 absolute z-10 bottom-12 right-8 w-12 h-12 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
          >
            {/* Cart icon */}
            <img src="circleIcon.png" className="w-20" alt="Add To Cart" />
          </button>
        )}

        <div className="">
          <div className="font-thin text-[18px]">{product.name}</div>
          <div className="font-bold text-[18px] pt-2">
            {price?.currency?.symbol}
            {price.amount.toFixed(2)}
          </div>
        </div>
      </div>
      {/*  */}
    </>
  );
};
