
import React from "react";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";

interface HeroSectionProps {
  title: string;
  subtitle: string;
  imageUrl: string;
  discountLabel?: string;
}

const HeroSection = ({ title, subtitle, imageUrl, discountLabel }: HeroSectionProps) => {
  return (
    <section className="hero-section py-16">
      <div className="container mx-auto px-4">
        <div className="flex flex-col lg:flex-row items-center justify-between">
          <div className="lg:w-1/2 mb-8 lg:mb-0">
            <div className="text-sm font-semibold text-gray-600 uppercase mb-2">ONLINE MEDICAL SUPPLIES</div>
            <h1 className="text-5xl md:text-6xl font-bold text-gray-900 mb-4">{title}</h1>
            <p className="text-xl text-gray-700 mb-6">{subtitle}</p>
            <div className="flex space-x-4">
              <Link to="/shop">
                <Button className="bg-primary hover:bg-primary/90 text-white px-8 py-2 rounded-md">
                  SHOP NOW
                </Button>
              </Link>
            </div>
          </div>
          
          <div className="lg:w-1/2 relative">
            <img src={imageUrl} alt="Hero Product" className="max-w-full h-auto rounded-lg shadow-lg" />
            {discountLabel && (
              <div className="absolute top-4 right-4 bg-pharma-secondary text-white rounded-full h-20 w-20 flex items-center justify-center flex-col">
                <span className="font-bold">{discountLabel}</span>
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
