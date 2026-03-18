
export interface Feature {
  title: string;
  description: string;
  icon: string;
}

export interface ValueItem {
  title: string;
  text: string;
}

export interface CorporateProfile {
  name: string;
  description: {
    whatIs: string;
    problemSolved: string;
    targetAudience: string;
  };
  valueProposition: {
    benefits: string[];
    automation: string;
    localAdaptation: string;
  };
  features: Feature[];
  missionVision: {
    mission: string;
    vision: string;
    values: ValueItem[];
  };
  tone: string;
  positioning: string;
  taglines: string[];
}
