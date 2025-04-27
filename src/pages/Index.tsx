
import React from "react";
import RootLayout from "@/components/layouts/RootLayout";
import HeroSection from "@/components/ui/custom/HeroSection";
import FeaturesSection from "@/components/ui/custom/FeaturesSection";
import ProductCard from "@/components/ui/custom/ProductCard";
import CategoryCard from "@/components/ui/custom/CategoryCard";

const Index = () => {
  const featuredProducts = [
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
  ];
  
  const newArrivals = [
    {
      id: 5,
      name: "Pure Enrichment Humidifier",
      price: 40,
      image: "https://i.imgur.com/jTdLhTn.png",
      rating: 4,
      isSoldOut: true,
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
      isSoldOut: false,
    },
    {
      id: 8,
      name: "ORB Hair Vitamins",
      price: 30,
      image: "https://i.imgur.com/mZbEpGT.png",
      rating: 4,
      isSoldOut: true,
    },
  ];

  return (
    <RootLayout>
      <HeroSection
        title="Get Your Vitamins & Minerals"
        subtitle="Browse our selection of high-quality health supplements"
        imageUrl="public/lovable-uploads/67409fad-a001-46b6-b4a2-0b14b8055c82.png"
        discountLabel="50%"
      />
      
      <FeaturesSection />
      
      <section className="py-12">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-center mb-8">Featured Items</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {featuredProducts.map(product => (
              <ProductCard key={product.id} {...product} />
            ))}
          </div>
        </div>
      </section>
      
      <section className="py-12 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <CategoryCard
              title="Protein Supplement"
              subtitle="Your Day-life Protection"
              price="19$"
              image="public/lovable-uploads/e339c297-bc00-47dd-b806-e80c5eda4522.png"
              link="/shop?category=supplements"
            />
            <CategoryCard
              title="Immunity Boosters"
              subtitle="GET UP TO 26%"
              price="19$"
              image="public/lovable-uploads/a493ef36-2307-4f49-a2bd-4cc7729a3f52.png"
              link="/shop?category=immunity"
            />
          </div>
        </div>
      </section>
      
      <section className="py-12">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-center mb-8">New Arrivals</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {newArrivals.map(product => (
              <div key={product.id} className="relative">
                <ProductCard {...product} />
                {product.isSoldOut && (
                  <div className="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 text-xs font-semibold rounded">
                    Sold Out
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>
    </RootLayout>
  );
};

export default Index;
