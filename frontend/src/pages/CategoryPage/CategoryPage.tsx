import { useQuery } from "@apollo/client/react";
import type { ProductsData } from "../../types";
import { GET_PRODUCTS } from "../../graphql/queries";
import { ProductCard } from "../../components/ProductCard/ProductCard";

type Props = {
  category: string;
};

function CategoryPage({ category }: Props) {
  const { data, loading, error } = useQuery<ProductsData>(GET_PRODUCTS, {
    variables: { category: category === "all" ? null : category },
  });

  if (loading) return <p className="mt-24 px-8">Loading...</p>;
  if (error) return <p className="mt-24 px-8">{error.message}</p>;

  return (
    <div className="px-4 sm:px-8 lg:px-20">
      {/* Category title */}
      <p className="py-10 sm:py-16 text-3xl sm:text-[42px]">
        {category.toUpperCase()}
      </p>

      {/* Responsive grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        {data?.products.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>
    </div>
  );
}

export default CategoryPage;
