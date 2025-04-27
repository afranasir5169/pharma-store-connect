
import React from "react";
import { useParams } from "react-router-dom";
import RootLayout from "@/components/layouts/RootLayout";
import ProductDetail from "@/components/ui/custom/ProductDetail";
import ProductCard from "@/components/ui/custom/ProductCard";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";

const ProductDetailPage = () => {
  const { id } = useParams<{ id: string }>();
  
  // This would typically be fetched from an API
  const product = {
    id: Number(id) || 2,
    name: "Biotin Complex with Coconut Oil",
    price: 90,
    discountPrice: 30,
    description: "Biotin Complex with Coconut Oil is a potent formula designed to support hair, skin, and nail health. This powerful supplement delivers 5,000mcg of biotin enhanced with coconut oil for better absorption. Regular use helps strengthen hair, reduce breakage, improve nail strength, and support healthy skin.",
    images: [
      "public/lovable-uploads/67409fad-a001-46b6-b4a2-0b14b8055c82.png",
      "https://i.imgur.com/6XQr4o8.png",
      "https://i.imgur.com/4cP5m6o.png",
      "https://i.imgur.com/Tki86s1.png",
    ],
    category: "Beauty & Health",
    sku: "BIO-5000-COO",
    requiresPrescription: false,
  };
  
  const relatedProducts = [
    {
      id: 3,
      name: "Vitamin D3 Gummies, Blueberry Taste",
      price: 40,
      image: "https://i.imgur.com/4cP5m6o.png",
      rating: 4,
    },
    {
      id: 8,
      name: "ORB Hair Vitamins",
      price: 30,
      image: "https://i.imgur.com/mZbEpGT.png",
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
      id: 1,
      name: "Hatch Baby Rest Night Light",
      price: 95,
      discountPrice: 55,
      image: "https://i.imgur.com/6XQr4o8.png",
      rating: 4,
      discountPercentage: 42,
    },
  ];

  return (
    <RootLayout>
      <div className="container mx-auto px-4">
        <ProductDetail {...product} />
        
        <section className="py-12">
          <Tabs defaultValue="description">
            <TabsList className="w-full justify-start">
              <TabsTrigger value="description">Description</TabsTrigger>
              <TabsTrigger value="additional">Additional Information</TabsTrigger>
              <TabsTrigger value="reviews">Reviews (4)</TabsTrigger>
            </TabsList>
            <TabsContent value="description" className="mt-6">
              <div className="prose max-w-none">
                <p>
                  Biotin Complex with Coconut Oil is a potent formula designed to support hair, skin, and nail health. This powerful supplement delivers 5,000mcg of biotin enhanced with coconut oil for better absorption.
                </p>
                <p>
                  Regular use helps strengthen hair, reduce breakage, improve nail strength, and support healthy skin. The addition of coconut oil enhances the bioavailability of biotin, making it more effective than standard biotin supplements.
                </p>
                <h3>Benefits:</h3>
                <ul>
                  <li>Promotes stronger, healthier hair</li>
                  <li>Supports nail strength and growth</li>
                  <li>Contributes to healthy, radiant skin</li>
                  <li>Enhanced with coconut oil for better absorption</li>
                  <li>Vegan and non-GMO formula</li>
                </ul>
              </div>
            </TabsContent>
            
            <TabsContent value="additional" className="mt-6">
              <div className="prose max-w-none">
                <table className="w-full">
                  <tbody>
                    <tr className="border-b">
                      <th className="text-left py-2 pr-4">Ingredients</th>
                      <td className="py-2">Biotin (5,000mcg), Coconut Oil, Vegetable Cellulose (capsule)</td>
                    </tr>
                    <tr className="border-b">
                      <th className="text-left py-2 pr-4">Suggested Use</th>
                      <td className="py-2">Take one capsule daily with water</td>
                    </tr>
                    <tr className="border-b">
                      <th className="text-left py-2 pr-4">Warnings</th>
                      <td className="py-2">Consult your physician before use if pregnant, nursing, or taking medications</td>
                    </tr>
                    <tr className="border-b">
                      <th className="text-left py-2 pr-4">Storage</th>
                      <td className="py-2">Store in a cool, dry place</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </TabsContent>
            
            <TabsContent value="reviews" className="mt-6">
              <div className="space-y-6">
                {[...Array(4)].map((_, index) => (
                  <div key={index} className="border-b pb-6 last:border-0">
                    <div className="flex justify-between mb-2">
                      <div className="font-medium">Customer {index + 1}</div>
                      <div className="text-sm text-gray-500">
                        {new Date(Date.now() - (index * 86400000)).toLocaleDateString()}
                      </div>
                    </div>
                    <div className="flex mb-2">
                      {[...Array(5)].map((_, starIndex) => (
                        <svg
                          key={starIndex}
                          className={`h-4 w-4 ${starIndex < 5 - index * 0.5 ? "text-yellow-400" : "text-gray-300"}`}
                          fill="currentColor"
                          viewBox="0 0 20 20"
                        >
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      ))}
                    </div>
                    <p className="text-gray-600">
                      {index === 0 && "This product has made a noticeable difference in my hair and nail strength after just 3 weeks of use. Highly recommend!"}
                      {index === 1 && "Great supplement, but the capsules are a bit large. Still, I'm seeing good results with my hair growth."}
                      {index === 2 && "Been using this for a month and my nails are definitely stronger. Will continue to purchase."}
                      {index === 3 && "Love this biotin complex! My hair feels thicker and healthier. The coconut oil addition really makes a difference compared to other biotin supplements I've tried."}
                    </p>
                  </div>
                ))}
              </div>
            </TabsContent>
          </Tabs>
        </section>
        
        <section className="py-12">
          <h2 className="text-3xl font-bold mb-8">Related Products</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {relatedProducts.map(product => (
              <ProductCard key={product.id} {...product} />
            ))}
          </div>
        </section>
      </div>
    </RootLayout>
  );
};

export default ProductDetailPage;
