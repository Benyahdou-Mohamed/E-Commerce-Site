import { useState } from "react";

type Props = {
  gallery: string[];
  productName: string;
};

const ProductGallery = ({ gallery, productName }: Props) => {
  const [selectedImage, setSelectedImage] = useState<number>(0);

  const handlePrev = () => {
    setSelectedImage((prev) => (prev === 0 ? gallery.length - 1 : prev - 1));
  };

  const handleNext = () => {
    setSelectedImage((prev) => (prev === gallery.length - 1 ? 0 : prev + 1));
  };

  return (
    <div className="flex flex-col sm:flex-row gap-3 lg:flex-row lg:flex-1">
      {/* Thumbnails */}
      <div className="flex flex-row sm:flex-col gap-2 overflow-x-auto sm:overflow-y-auto sm:max-h-[500px] sm:w-20 flex-shrink-0">
        {gallery.map((img, index) => (
          <img
            key={index}
            src={img}
            alt={`${productName} ${index + 1}`}
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
          src={gallery[selectedImage]}
          alt={productName}
          className="w-full h-full object-contain"
        />

        {/* Arrows */}
        {gallery.length > 1 && (
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
  );
};

export default ProductGallery;
