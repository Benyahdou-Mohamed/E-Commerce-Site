import { useQuery } from "@apollo/client/react";
import React from "react";
import { Link } from "react-router-dom";
import type { ProductsData } from "../types";
import { GET_PRODUCTS } from "../graphql/queries";
import { ProductCard } from "../components/ProductCard/ProductCard";

type Props = {
  category: string;
};

export const Home = ({ category }: Props) => {
  const { data, loading, error } = useQuery<ProductsData>(GET_PRODUCTS, {
    variables: { category: category === "all" ? null : category },
  });
  if (loading) return <p>Loading</p>;
  if (error) return <p>{error.message}</p>;
  return (
    <div className="">
      <div className="px-20">
        <p className="py-16 text-[42px]">{category.toUpperCase()}</p>

        <div className="grid lg:grid-cols-3 sm:grid-cols-2 md:grid-cols-2 gap-4">
          {/*  */}
          {data?.products.map((product) => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      </div>
    </div>
  );
};
