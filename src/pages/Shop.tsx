
import React, { useState } from "react";
import RootLayout from "@/components/layouts/RootLayout";
import ProductCard from "@/components/ui/custom/ProductCard";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Checkbox } from "@/components/ui/checkbox";
import { Label } from "@/components/ui/label";
import { Slider } from "@/components/ui/slider";

const Shop = () => {
  const [priceRange, setPriceRange] = useState([0, 100]);
  
  const products = [
    {
      id: 1,
      name: "Hatch Baby Rest Night Light",
      price: 95,
      discountPrice: 55,
      image: "https://i.imgur.com/6XQr4o8.png",
      rating: 4,
      discountPercentage: 42,
    },
    {
      id: 2,
      name: "Biotin Complex with Coconut Oil",
      price: 90,
      discountPrice: 30,
      image: "public/lovable-uploads/67409fad-a001-46b6-b4a2-0b14b8055c82.png",
      rating: 5,
    },
    {
      id: 3,
      name: "Vitamin D3 Gummies, Blueberry Taste",
      price: 40,
      image: "https://i.imgur.com/4cP5m6o.png",
      rating: 4,
    },
    {
      id: 4,
      name: "Blood Pressure Monitor",
      price: 24,
      image: "https://i.imgur.com/Tki86s1.png",
      rating: 5,
    },
    {
      id: 5,
      name: "Pure Enrichment Humidifier",
      price: 40,
      image: "https://i.imgur.com/jTdLhTn.png",
      rating: 4,
    },
    {
      id: 6,
      name: "Essential Oil Collection Set",
      price: 75,
      image: "https://i.imgur.com/RZVNirQ.png",
      rating: 5,
    },
    {
      id: 7,
      name: "Digital Blood Pressure Monitor",
      price: 60,
      image: "https://i.imgur.com/Tki86s1.png",
      rating: 5,
    },
    {
      id: 8,
      name: "ORB Hair Vitamins",
      price: 30,
      image: "https://i.imgur.com/mZbEpGT.png",
      rating: 4,
    },
  ];

  const categories = [
    { id: "vitamins", label: "Vitamins & Supplements" },
    { id: "medications", label: "Medications" },
    { id: "firstaid", label: "First Aid" },
    { id: "skincare", label: "Skin Care" },
    { id: "devices", label: "Medical Devices" },
  ];

  return (
    <RootLayout>
      <div className="container mx-auto px-4 py-8">
        <div className="flex flex-col md:flex-row">
          <aside className="md:w-64 flex-shrink-0 mb-8 md:mb-0 md:mr-8">
            <div className="bg-white p-6 rounded-lg shadow-sm border">
              <h3 className="font-medium text-lg mb-4">Filters</h3>
              
              <div className="mb-6">
                <h4 className="font-medium mb-2">Search</h4>
                <Input placeholder="Search products..." />
              </div>
              
              <div className="mb-6">
                <h4 className="font-medium mb-2">Categories</h4>
                <div className="space-y-2">
                  {categories.map(category => (
                    <div key={category.id} className="flex items-center">
                      <Checkbox id={category.id} />
                      <Label htmlFor={category.id} className="ml-2 text-sm">
                        {category.label}
                      </Label>
                    </div>
                  ))}
                </div>
              </div>
              
              <div className="mb-6">
                <h4 className="font-medium mb-2">Price Range</h4>
                <div className="pt-4">
                  <Slider
                    defaultValue={[0, 100]}
                    max={100}
                    step={1}
                    value={priceRange}
                    onValueChange={setPriceRange}
                  />
                  <div className="flex justify-between mt-2 text-sm">
                    <span>${priceRange[0]}</span>
                    <span>${priceRange[1]}</span>
                  </div>
                </div>
              </div>
              
              <div>
                <h4 className="font-medium mb-2">Prescription Required</h4>
                <div className="flex items-center">
                  <Checkbox id="prescription" />
                  <Label htmlFor="prescription" className="ml-2 text-sm">
                    No Prescription
                  </Label>
                </div>
              </div>
            </div>
          </aside>
          
          <div className="flex-1">
            <div className="flex justify-between items-center mb-6">
              <h2 className="text-2xl font-bold">All Products</h2>
              
              <div className="flex items-center space-x-2">
                <span className="text-sm text-gray-600">Sort by:</span>
                <Select defaultValue="featured">
                  <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Sort by" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="featured">Featured</SelectItem>
                    <SelectItem value="price-asc">Price: Low to High</SelectItem>
                    <SelectItem value="price-desc">Price: High to Low</SelectItem>
                    <SelectItem value="newest">Newest First</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {products.map(product => (
                <ProductCard key={product.id} {...product} />
              ))}
            </div>
          </div>
        </div>
      </div>
    </RootLayout>
  );
};

export default Shop;
