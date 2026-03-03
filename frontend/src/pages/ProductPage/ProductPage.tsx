import { useQuery } from "@apollo/client/react";
import React, { useState } from "react";
import { Link } from "react-router-dom";
import { useParams } from "react-router-dom";
import type { Product, ProductData } from "../../types";
import { GET_PRODUCT } from "../../graphql/queries";
import { useCart } from "../../context/CartContext";
import Attribute from "../../components/ProductCard/Attribute";

type Props = {};

export const ProductPage = (props: Props) => {
  const { id } = useParams<{ id: string }>();
  const [selectedImage, setSelectedImage] = useState<number>(0);
  const { addToCart } = useCart();
  const [selectedAttributes, setSelectedAttributes] = useState<
    Record<string, string>
  >({});
  const { data, loading, error } = useQuery<ProductData>(GET_PRODUCT, {
    variables: { id },
  });
  const product = data?.product;

  const handlePrev = () => {
    setSelectedImage((prev) =>
      prev === 0 ? (product?.gallery?.length ?? 1) - 1 : prev - 1,
    );
  };
  const handleNext = () => {
    const len = product?.gallery.length ?? 1;
    setSelectedImage((prev) => (prev === len - 1 ? 0 : prev + 1));
  };
  const handleSelect = (attID: string, value: string) => {
    setSelectedAttributes((prev) => ({ ...prev, [attID]: value }));
    //console.log(selectedAttributes);
  };

  function handleAddToCart() {
    if (!canAddToCart) return;
    if (!product) return;
    if (!canAddToCart) return;
    addToCart(product, selectedAttributes);
  }

  // Check all attributes are selected
  const allSelected: boolean =
    product?.attributes?.every((attr) => selectedAttributes[attr.id]) ?? true;

  const canAddToCart: boolean = !!product?.inStock && allSelected;
  return (
    <>
      <section className="mt-24">
        {/* LEFT — thumbnails with scroll */}
        <div className="flex flex-1  ">
          <div className="overflow-y-auto max-h-[500px]">
            <div className="w-[80px] flex-shrink-0  cursor-pointer">
              {product?.gallery.map((img, index) => (
                <img
                  data-testid="product-gallery"
                  className="w-full object-cover"
                  key={index}
                  src={img}
                  alt="Sunset in the mountains"
                  onClick={() => {
                    setSelectedImage(index);
                  }}
                />
              ))}
            </div>
          </div>
          {/* CENTER — main image with arrows */}
          <div className="relative flex flex-1 max-h-[500px]">
            {" "}
            {product?.gallery?.length !== undefined &&
              product?.gallery?.length !== 1 && (
                <button
                  onClick={handlePrev}
                  className="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-60 text-white px-3 py-2 hover:bg-opacity-80"
                >
                  ‹
                </button>
              )}
            <img
              className="w-full"
              key={selectedImage}
              src={product?.gallery[selectedImage]}
              alt="Sunset in the mountains"
              data-testid="product-gallery"
            />
            {product?.gallery?.length !== undefined &&
              product?.gallery?.length !== 1 && (
                <button
                  onClick={handleNext}
                  className="absolute right-2 top-1/2 -translate-y-1/2 rounded-full cursor-pointer bg-black text-white px-3 py-2"
                >
                  ›
                </button>
              )}
          </div>
          {/* RIGHT — product details */}
          <div className="flex flex-1 flex-col px-15">
            <div className="max-w-96">
              {/* Name */}
              <div className="text-3xl mb-8">{product?.name}</div>
              <div className="">
                {/* Attributes */}
                {product?.attributes?.map((attribute) => (
                  <Attribute
                    key={attribute.id}
                    attribute={attribute}
                    selectedAttributes={selectedAttributes}
                    onSelect={handleSelect}
                  />
                ))}
                {/* Price */}
                <div className="my-2">
                  <div className="font-bold">PRICE:</div>
                  <div className="text-2xl font-bold my-3">
                    {product?.prices[0].currency.label}{" "}
                    {product?.prices[0].amount.toFixed(2)}
                  </div>
                </div>
                {/* Add to cart button */}
                <button
                  data-testid="add-to-cart"
                  onClick={handleAddToCart}
                  className={`w-3xs py-3 text-white font-medium tracking-wider transition-colors ${
                    canAddToCart
                      ? "bg-green-500 hover:bg-green-600 cursor-pointer"
                      : "bg-gray-300 cursor-not-allowed"
                  }`}
                >
                  ADD TO CART
                </button>
                <div
                  data-testid="product-description"
                  className="mt-8 text-gray-600 text-sm leading-relaxed"
                >
                  {product?.description.replace(/<[^>]*>/g, "")}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
};
