import { useQuery } from "@apollo/client/react";
import React, { useState } from "react";
import { useParams } from "react-router-dom";
import type { ProductData } from "../../types";
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

          {/* PRODUCT DETAILS SECTION */}
          <div className="flex flex-col lg:w-80 xl:w-96">
            {/* Name */}
            <h1 className="text-2xl sm:text-3xl font-semibold mb-4">
              {product.name}
            </h1>

            {/* Attributes */}
            {product.attributes.map((attribute, index) => (
              <Attribute
                key={index}
                attribute={attribute}
                selectedAttributes={selectedAttributes}
                onSelect={handleSelect}
              />
            ))}

            {/* Price */}
            <div className="my-4">
              <p className="font-bold uppercase text-sm tracking-wide">
                Price:
              </p>
              <p className="text-2xl font-bold mt-1">
                {product.prices[0].currency.symbol}
                {product.prices[0].amount.toFixed(2)}
              </p>
            </div>

            {/* Add to cart */}
            <button
              data-testid="add-to-cart"
              onClick={handleAddToCart}
              disabled={!canAddToCart}
              className={`
              w-full sm:w-80 py-3 sm:py-4
              text-white font-medium tracking-widest uppercase text-sm
              transition-colors
              ${
                canAddToCart
                  ? "bg-green-500 hover:bg-green-600 cursor-pointer"
                  : "bg-gray-300 cursor-not-allowed"
              }
            `}
            >
              ADD TO CART
            </button>

            {/* Description */}
            <div
              data-testid="product-description"
              className="mt-6 text-gray-600 text-sm leading-relaxed"
            >
              {product.description.replace(/<[^>]*>/g, "")}
            </div>
          </div>
        </div>
      </section>
    </>
  );
};
