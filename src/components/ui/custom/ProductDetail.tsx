
import React, { useState } from "react";
import { Button } from "@/components/ui/button";
import { Heart, Minus, Plus, Upload } from "lucide-react";
import { useToast } from "@/hooks/use-toast";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Label } from "@/components/ui/label";

interface ProductDetailProps {
  id: number;
  name: string;
  price: number;
  discountPrice?: number;
  description: string;
  images: string[];
  category: string;
  sku: string;
  requiresPrescription: boolean;
}

const ProductDetail = ({
  id,
  name,
  price,
  discountPrice,
  description,
  images,
  category,
  sku,
  requiresPrescription,
}: ProductDetailProps) => {
  const { toast } = useToast();
  const [selectedImage, setSelectedImage] = useState(0);
  const [quantity, setQuantity] = useState(1);
  const [selectedQuantity, setSelectedQuantity] = useState("30");
  const [prescription, setPrescription] = useState<File | null>(null);

  const handleQuantityChange = (action: "increase" | "decrease") => {
    if (action === "increase") {
      setQuantity(quantity + 1);
    } else if (quantity > 1) {
      setQuantity(quantity - 1);
    }
  };

  const handleAddToCart = () => {
    if (requiresPrescription && !prescription) {
      toast({
        title: "Prescription Required",
        description: "Please upload a prescription before adding this item to cart.",
        variant: "destructive",
      });
      return;
    }

    toast({
      title: "Added to Cart",
      description: `${name} (${selectedQuantity} Tablets) has been added to your cart.`,
    });
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      setPrescription(e.target.files[0]);
      toast({
        title: "Prescription Uploaded",
        description: `File ${e.target.files[0].name} has been uploaded.`,
      });
    }
  };

  const handleAddToWishlist = () => {
    toast({
      title: "Added to Wishlist",
      description: `${name} has been added to your wishlist.`,
    });
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="flex flex-col md:flex-row -mx-4">
        <div className="md:w-1/2 px-4 mb-8 md:mb-0">
          <div className="sticky top-20">
            <div className="mb-4 rounded-lg overflow-hidden border">
              <img src={images[selectedImage]} alt={name} className="w-full h-auto" />
            </div>
            <div className="grid grid-cols-4 gap-2">
              {images.map((image, index) => (
                <div
                  key={index}
                  className={`cursor-pointer border rounded-md overflow-hidden ${
                    selectedImage === index ? "ring-2 ring-primary" : ""
                  }`}
                  onClick={() => setSelectedImage(index)}
                >
                  <img src={image} alt={`${name}-${index}`} className="w-full h-auto" />
                </div>
              ))}
            </div>
          </div>
        </div>
        
        <div className="md:w-1/2 px-4">
          <div className="breadcrumbs text-sm text-gray-500 mb-4">
            Home / {category} / {name}
          </div>
          
          <h1 className="text-3xl font-bold text-gray-900 mb-2">{name}</h1>
          
          <div className="flex items-center mb-4">
            <div className="flex">
              {[...Array(5)].map((_, index) => (
                <svg
                  key={index}
                  className="h-5 w-5 text-yellow-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              ))}
            </div>
            <span className="ml-2 text-sm text-gray-500">(4 customer reviews)</span>
          </div>
          
          <div className="mb-6">
            {discountPrice ? (
              <div className="flex items-center">
                <span className="text-3xl font-bold text-gray-900">${discountPrice}</span>
                <span className="ml-2 text-xl text-gray-500 line-through">${price}</span>
              </div>
            ) : (
              <span className="text-3xl font-bold text-gray-900">${price}</span>
            )}
          </div>
          
          <div className="prose prose-sm text-gray-600 mb-6">
            <p>{description}</p>
          </div>
          
          <div className="mb-6">
            <h3 className="font-medium text-gray-900 mb-2">QUANTITY</h3>
            <RadioGroup value={selectedQuantity} onValueChange={setSelectedQuantity} className="flex flex-wrap gap-4">
              <div className="flex items-center space-x-2">
                <RadioGroupItem value="30" id="r1" />
                <Label htmlFor="r1">30 Tablets</Label>
              </div>
              <div className="flex items-center space-x-2">
                <RadioGroupItem value="60" id="r2" />
                <Label htmlFor="r2">60 Tablets</Label>
              </div>
              <div className="flex items-center space-x-2">
                <RadioGroupItem value="90" id="r3" />
                <Label htmlFor="r3">90 Tablets</Label>
              </div>
            </RadioGroup>
          </div>
          
          {requiresPrescription && (
            <div className="mb-6">
              <h3 className="font-medium text-gray-900 mb-2">PRESCRIPTION</h3>
              <div className="flex items-center">
                <Button variant="outline" className="flex items-center gap-2" onClick={() => document.getElementById("prescription-upload")?.click()}>
                  <Upload className="h-4 w-4" />
                  Upload Prescription
                </Button>
                <input
                  type="file"
                  id="prescription-upload"
                  className="hidden"
                  accept="image/*,.pdf"
                  onChange={handleFileChange}
                />
                {prescription && (
                  <span className="ml-2 text-sm text-green-500">
                    {prescription.name} uploaded
                  </span>
                )}
              </div>
            </div>
          )}
          
          <div className="flex items-center mb-6">
            <div className="flex items-center border rounded-md mr-4">
              <button
                className="px-3 py-2"
                onClick={() => handleQuantityChange("decrease")}
              >
                <Minus className="h-4 w-4" />
              </button>
              <span className="px-4 py-2 border-l border-r">{quantity}</span>
              <button
                className="px-3 py-2"
                onClick={() => handleQuantityChange("increase")}
              >
                <Plus className="h-4 w-4" />
              </button>
            </div>
            
            <Button className="flex-grow bg-primary hover:bg-primary/90" onClick={handleAddToCart}>
              ADD TO CART
            </Button>
            
            <Button variant="ghost" className="ml-2" onClick={handleAddToWishlist}>
              <Heart className="h-5 w-5" />
            </Button>
          </div>
          
          <div className="border-t border-gray-200 pt-4 text-sm">
            <p className="mb-1">SKU: {sku}</p>
            <p className="mb-1">Category: {category}</p>
            <p className="mb-1">Prescription Required: {requiresPrescription ? "Yes" : "No"}</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetail;
