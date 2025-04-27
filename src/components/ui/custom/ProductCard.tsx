
import React from "react";
import { Link } from "react-router-dom";
import { Heart } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useToast } from "@/hooks/use-toast";

interface ProductCardProps {
  id: number;
  name: string;
  price: number;
  discountPrice?: number;
  image: string;
  rating: number;
  discountPercentage?: number;
}

const ProductCard = ({
  id,
  name,
  price,
  discountPrice,
  image,
  rating,
  discountPercentage,
}: ProductCardProps) => {
  const { toast } = useToast();

  const handleAddToWishlist = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();

    toast({
      title: "Added to Wishlist",
      description: `${name} has been added to your wishlist.`,
    });
  };

  return (
    <div className="product-card group bg-white rounded-lg overflow-hidden shadow border">
      <Link to={`/product/${id}`} className="block relative">
        {discountPercentage && (
          <div className="absolute top-2 left-2 bg-pharma-secondary text-white text-xs font-semibold px-2 py-1 rounded">
            -{discountPercentage}%
          </div>
        )}
        <img src={image} alt={name} className="w-full h-48 object-cover object-center" />
        <button
          onClick={handleAddToWishlist}
          className="absolute top-2 right-2 p-2 bg-white bg-opacity-70 rounded-full hover:bg-opacity-100 transition"
        >
          <Heart className="h-5 w-5 text-pharma-secondary" />
        </button>
      </Link>
      
      <div className="p-4">
        <Link to={`/product/${id}`}>
          <h3 className="font-medium text-gray-900 mb-1 hover:text-primary transition">{name}</h3>
        </Link>
        
        <div className="flex items-center mb-2">
          {[...Array(5)].map((_, index) => (
            <svg
              key={index}
              className={`h-4 w-4 ${
                index < rating ? "text-yellow-400" : "text-gray-300"
              }`}
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
              />
            </svg>
          ))}
        </div>
        
        <div className="flex justify-between items-center">
          <div>
            {discountPrice ? (
              <div className="flex items-center">
                <span className="text-lg font-bold text-gray-900">${discountPrice}</span>
                <span className="ml-2 text-sm text-gray-500 line-through">${price}</span>
              </div>
            ) : (
              <span className="text-lg font-bold text-gray-900">${price}</span>
            )}
          </div>
          
          <Button
            onClick={() => {
              toast({
                title: "Added to Cart",
                description: `${name} has been added to your cart.`,
              });
            }}
            size="sm"
            className="bg-primary hover:bg-primary/90"
          >
            Add to Cart
          </Button>
        </div>
      </div>
    </div>
  );
};

export default ProductCard;
