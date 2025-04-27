
import React from "react";
import RootLayout from "@/components/layouts/RootLayout";
import { Button } from "@/components/ui/button";
import { X, ShoppingCart } from "lucide-react";
import { useToast } from "@/hooks/use-toast";

const Wishlist = () => {
  const { toast } = useToast();

  const wishlistItems = [
    {
      id: 1,
      name: "Hatch Baby Rest Night Light",
      price: 55,
      originalPrice: 95,
      image: "https://i.imgur.com/6XQr4o8.png",
    },
    {
      id: 2,
      name: "Biotin Complex with Coconut Oil",
      price: 30,
      originalPrice: 90,
      image: "public/lovable-uploads/67409fad-a001-46b6-b4a2-0b14b8055c82.png",
    },
    {
      id: 3,
      name: "Vitamin D3 Gummies, Blueberry Taste",
      price: 40,
      image: "https://i.imgur.com/4cP5m6o.png",
    },
  ];
  
  const handleRemoveItem = (id: number) => {
    toast({
      title: "Item Removed",
      description: `Item has been removed from your wishlist.`,
    });
  };
  
  const handleAddToCart = (id: number, name: string) => {
    toast({
      title: "Added to Cart",
      description: `${name} has been added to your cart.`,
    });
  };

  return (
    <RootLayout>
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold mb-6">My Wishlist</h1>
        
        {wishlistItems.length > 0 ? (
          <div className="bg-white rounded-lg shadow-sm overflow-hidden">
            <table className="min-w-full">
              <thead>
                <tr className="border-b bg-gray-50">
                  <th className="py-3 px-6 text-left">Product</th>
                  <th className="py-3 px-6 text-center">Price</th>
                  <th className="py-3 px-6 text-center">Stock Status</th>
                  <th className="py-3 px-6 text-center">Actions</th>
                  <th className="py-3 px-6 text-right"></th>
                </tr>
              </thead>
              
              <tbody>
                {wishlistItems.map((item) => (
                  <tr key={item.id} className="border-b">
                    <td className="py-4 px-6">
                      <div className="flex items-center">
                        <img src={item.image} alt={item.name} className="w-16 h-16 object-cover mr-4" />
                        <span className="font-medium">{item.name}</span>
                      </div>
                    </td>
                    
                    <td className="py-4 px-6 text-center">
                      {item.originalPrice && item.originalPrice > item.price ? (
                        <div>
                          <span className="text-lg font-medium">${item.price}</span>
                          <span className="text-sm text-gray-500 line-through ml-2">${item.originalPrice}</span>
                        </div>
                      ) : (
                        <span className="text-lg font-medium">${item.price}</span>
                      )}
                    </td>
                    
                    <td className="py-4 px-6 text-center">
                      <span className="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">In Stock</span>
                    </td>
                    
                    <td className="py-4 px-6 text-center">
                      <Button
                        onClick={() => handleAddToCart(item.id, item.name)}
                        className="flex items-center bg-primary hover:bg-primary/90"
                        size="sm"
                      >
                        <ShoppingCart className="mr-1 h-4 w-4" />
                        Add to Cart
                      </Button>
                    </td>
                    
                    <td className="py-4 px-6 text-right">
                      <button
                        onClick={() => handleRemoveItem(item.id)}
                        className="text-gray-500 hover:text-red-500"
                      >
                        <X className="h-5 w-5" />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <div className="text-center py-12 bg-white rounded-lg shadow-sm">
            <div className="text-gray-500 mb-4">Your wishlist is empty</div>
            <Button className="bg-primary hover:bg-primary/90">Continue Shopping</Button>
          </div>
        )}
      </div>
    </RootLayout>
  );
};

export default Wishlist;
