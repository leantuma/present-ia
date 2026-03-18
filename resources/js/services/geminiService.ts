
import { GoogleGenAI } from "@google/genai";
import { PROFILE_DATA } from "../constants";

const ai = new GoogleGenAI({ apiKey: process.env.API_KEY || '' });

export const askAboutPresentIA = async (question: string) => {
  const model = 'gemini-3-flash-preview';
  
  const systemInstruction = `
    Actuá como un consultor senior en branding y RRHH de Present-IA.
    Usá el siguiente perfil corporativo como base para tus respuestas:
    ${JSON.stringify(PROFILE_DATA)}
    
    Tus respuestas deben ser en español de Argentina, con tono profesional y corporativo.
    No uses emojis.
    Si te preguntan algo que no está en el perfil, extrapolá basándote en la identidad de la marca como una solución SaaS moderna e inteligente para Argentina.
  `;

  try {
    const response = await ai.models.generateContent({
      model,
      contents: question,
      config: {
        systemInstruction,
        temperature: 0.7,
      },
    });

    return response.text;
  } catch (error) {
    console.error("Error calling Gemini API:", error);
    return "Hubo un error al procesar tu consulta. Por favor, intentá nuevamente en unos momentos.";
  }
};
