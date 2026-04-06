import { useState } from "react";
import { useParams } from "react-router-dom";
import { useQuery } from "@apollo/client/react";

import type { ProductData } from "../../types";
import { GET_PRODUCT } from "../../graphql/queries";
import { useCart } from "../../context/CartContext";

import ProductGallery from "../../components/ProductPage/ProductGallery";
import ProductDetails from "../../components/ProductPage/ProductDetails";

export const ProductPage = () => {
  const { id } = useParams<{ id: string }>();

  const { addToCart } = useCart();

  const [selectedAttributes, setSelectedAttributes] = useState<
    Record<string, string>
  >({});

  const { data, loading, error } = useQuery<ProductData>(GET_PRODUCT, {
    variables: { id },
  });

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error</p>;

  const product = data?.product;

  if (!product) return <p>Not found</p>;

  // ── Cart logic ───────────────────────────────────────────
  const allSelected: boolean =
    product.attributes.every((attr) => selectedAttributes[attr.id]) ?? true;

  const canAddToCart: boolean = !!product.inStock && allSelected;

  const handleSelect = (attID: string, value: string) => {
    setSelectedAttributes((prev) => ({ ...prev, [attID]: value }));
  };

  const handleAddToCart = () => {
    if (!canAddToCart) return;
    if (!product) return;
    addToCart(product, selectedAttributes);
  };

  // ── Render ───────────────────────────────────────────────
  return (
    <>
      <section className="mt-16 px-4 sm:px-8 lg:px-16 pb-16">
        <div className="flex flex-col lg:flex-row gap-6">
          <ProductGallery
            gallery={product.gallery}
            productName={product.name}
          />

          <ProductDetails
            name={product.name}
            description={product.description}
            prices={product.prices}
            attributes={product.attributes}
            selectedAttributes={selectedAttributes}
            canAddToCart={canAddToCart}
            onSelect={handleSelect}
            onAddToCart={handleAddToCart}
          />
        </div>
      </section>
    </>
  );
};
