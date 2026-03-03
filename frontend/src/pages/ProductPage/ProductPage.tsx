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
  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error</p>;
  if (!product) return <p>Not found</p>;

  // Check all attributes are selected
  const allSelected: boolean =
    product?.attributes?.every((attr) => selectedAttributes[attr.id]) ?? true;

  const canAddToCart: boolean = !!product?.inStock && allSelected;
  return (
    <>
      <section className="mt-16 px-4 sm:px-8 lg:px-16 pb-16">
        <div className="flex flex-col lg:flex-row gap-6">
          {/* ── GALLERY SECTION ── */}
          <div className="flex flex-col sm:flex-row gap-3 lg:flex-row lg:flex-1">
            {/* Thumbnails — horizontal on mobile */}
            <div className="flex flex-row sm:flex-col gap-2 overflow-x-auto sm:overflow-y-auto sm:max-h-[500px] sm:w-20 flex-shrink-0">
              {product.gallery.map((img, index) => (
                <img
                  data-testid="product-gallery"
                  className="w-full object-cover"
                  key={index}
                  src={img}
                  onClick={() => setSelectedImage(index)}
                  className={`
                  w-16 h-16 sm:w-20 sm:h-20
                  object-cover flex-shrink-0 cursor-pointer border-2 transition-all
                  ${
                    selectedImage === index
                      ? "border-green-500"
                      : "border-transparent hover:border-gray-300"
                  }
                `}
                />
              ))}
            </div>

            {/* Main image */}
            <div
              data-testid="product-gallery"
              className="relative flex-1 max-h-[300px] sm:max-h-[400px] lg:max-h-[500px]"
            >
              <img
                key={selectedImage}
                src={product.gallery[selectedImage]}
                className="w-full h-full object-contain"
              />

              {/* Arrows — only show if more than 1 image */}
              {product.gallery.length > 1 && (
                <>
                  <button
                    onClick={handlePrev}
                    className="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-60 text-white px-3 py-2 hover:bg-opacity-80"
                  >
                    ‹
                  </button>
                  <button
                    onClick={handleNext}
                    className="absolute right-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-60 text-white px-3 py-2 hover:bg-opacity-80"
                  >
                    ›
                  </button>
                </>
              )}
            </div>
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
